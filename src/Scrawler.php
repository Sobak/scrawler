<?php

declare(strict_types=1);

namespace Sobak\Scrawler;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use Psr\Http\Message\ResponseInterface;
use Sobak\Scrawler\Block\ResultWriter\FilenameProvider\FilenameProviderInterface;
use Sobak\Scrawler\Block\ResultWriter\FileResultWriterInterface;
use Sobak\Scrawler\Block\ResultWriter\ResultWriterInterface;
use Sobak\Scrawler\Client\ClientFactory;
use Sobak\Scrawler\Client\Response\ContentType;
use Sobak\Scrawler\Client\Response\Elements\Url;
use Sobak\Scrawler\Client\Response\StatusCode;
use Sobak\Scrawler\Configuration\Configuration;
use Sobak\Scrawler\Configuration\ConfigurationChecker;
use Sobak\Scrawler\Entity\EntityMapper;
use Sobak\Scrawler\Output\Outputter;
use Sobak\Scrawler\Output\OutputWriterInterface;
use Sobak\Scrawler\Support\LogWriter;
use Sobak\Scrawler\Support\Utils;
use Symfony\Component\DomCrawler\Crawler;

class Scrawler
{
    const VERSION = '0.3.0-dev';

    /** @var Configuration */
    protected $configuration;

    protected $crawledUrls;

    protected $initializedResultWrites;

    /** @var Url */
    protected $initialUrl;

    protected $logWriter;

    protected $onceMatchedObjects = [];

    protected $output;

    protected $visitedUrls = [];

    public function __construct(Configuration $configuration, string $outputDirectory)
    {
        $configurationChecker = new ConfigurationChecker();
        $configurationChecker->checkConfiguration($configuration);

        $this->output = new Outputter(
            $outputDirectory,
            Utils::slugify($configuration->getOperationName())
        );

        $this->configuration = $configuration;
        $this->logWriter = new LogWriter($this->configuration->getLogWriters(), $this->output);
    }

    public function run()
    {
        $timeStart = microtime(true);

        $this->logWriter->notice('Started "' . $this->configuration->getOperationName() . '" operation');

        $client = ClientFactory::buildInstance($this->configuration->getClientConfigurationProviders());
        $initialUrl = $this->initialUrl = new Url($this->configuration->getBaseUrl());

        try {
            $robotsTxt = $this->fetchRobotsTxt($client, $initialUrl);
        } catch (ConnectException $exception) {
            return -1;
        }

        if ($robotsTxt && $this->configuration->getRobotsParser()) {
            $this->configuration->getRobotsParser()->parseRobotsTxt($robotsTxt);
        } else {
            // Unset robots parser if there is no robots.txt file
            $this->configuration->setRobotsParser(null);
        }

        $this->crawledUrls = 0;

        $this->makeRequest($client, $initialUrl);

        $duration = round(microtime(true) - $timeStart, 2);
        $this->logWriter->notice("Finished in {$duration}s");

        return 0;
    }

    protected function makeRequest(Client $client, Url $url): void
    {
        $canonicalUrl = $url->getUrl();

        $this->visitedUrls[$canonicalUrl] = true;

        try {
            $response = $client->request('GET', $canonicalUrl);
        } catch (ConnectException $exception) {
            $this->logWriter->critical("GET {$canonicalUrl} Could not connect to the server");
            return;
        }

        $contentType = new ContentType($response->getHeader('content-type'));
        $robotsParser = $this->configuration->getRobotsParser();
        $statusCode = new StatusCode($response->getStatusCode());

        if ($contentType->isProcessable() === false) {
            $this->logWriter->warning("GET {$canonicalUrl} Skipped due to unprocessable content type ({$contentType->getType()})");
        } elseif ($statusCode->isProcessable() === false) {
            $this->logWriter->notice("GET {$canonicalUrl} Skipped due to unprocessable response code ({$statusCode->getCode()})");
        } elseif ($robotsParser && $robotsParser->isAllowed($url) === false) {
            $this->logWriter->notice("GET {$canonicalUrl} Blocked by the robots.txt");
        } else {
            $this->logWriter->info("GET {$canonicalUrl}");
            $this->processResponse($response);
        }

        ++$this->crawledUrls;

        if ($this->isUrlsLimitReached()) {
            $this->logWriter->notice('Reached crawled URLs limit of ' . $this->configuration->getMaxCrawledUrls());
            return;
        }

        // Gather list of next URLs using rules specified in configuration
        foreach ($this->configuration->getUrlListProviders() as $urlListProvider) {
            $urlListProvider->setBaseUrl($this->initialUrl);
            $urlListProvider->setCurrentUrl($url);
            $urlListProvider->setResponse($response);

            foreach ($urlListProvider->getUrls() as $url) {
                $nextCanonicalUrl = $url->getUrl();
                if (
                    (isset($this->visitedUrls[$nextCanonicalUrl]) && $this->visitedUrls[$nextCanonicalUrl] === true)
                    || $this->isUrlsLimitReached()
                ) {
                    continue;
                }

                $this->makeRequest($client, $url);
            }
        }
    }

    protected function processResponse(ResponseInterface $response): void
    {
        $responseBody = $response->getBody()->getContents();

        if (empty(Utils::trimWhitespace($responseBody))) {
            $this->logWriter->warning('Received empty response');
            return;
        }

        foreach ($this->configuration->getObjectDefinitions() as $objectListName => $objectDefinition) {
            if ($objectDefinition->isOnce()) {
                $objectId = spl_object_id($objectDefinition);

                if (in_array($objectId, $this->onceMatchedObjects)) {
                    continue;
                }

                $this->onceMatchedObjects[] = $objectId;
            }

            $objectDefinition->getMatcher()->setCrawler(new Crawler($responseBody));
            $matchesList = $objectDefinition->getMatcher()->match();

            // Iterate over single found object
            foreach ($matchesList as $match) {
                $this->logWriter->debug("Matched object for {$objectListName}");

                $objectResult = [];
                foreach ($objectDefinition->getFieldDefinitions() as $fieldName => $matcher) {
                    $matcher->setCrawler(new Crawler($match));

                    $objectResult[$fieldName] = $matcher->match();
                }

                // Map object result to entities and write them
                foreach ($objectDefinition->getEntityMappings() as $entityClass) {
                    $entity = EntityMapper::resultToEntity($objectResult, $entityClass);

                    $this->logWriter->debug("Mapped object to the {$entityClass} entity");

                    $resultWriters = $objectDefinition->getResultWriters();

                    if (isset($resultWriters[$entityClass]) === false) {
                        continue;
                    }

                    /** @var ResultWriterInterface $resultWriter */
                    foreach ($resultWriters[$entityClass] as $resultWriter) {
                        $resultWriter->setEntity($entityClass);
                        $resultWriter->setLogWriter($this->logWriter);

                        $this->logWriter->debug('Writing entity down using ' . get_class($resultWriter));

                        // Generate the filename for FileResultWriters
                        if ($resultWriter instanceof FileResultWriterInterface) {
                            /** @var FilenameProviderInterface $filenameProvider */
                            $filenameProvider = $resultWriter->getConfiguration()['filename'];
                            $filename = $filenameProvider->generateFilename($entity);
                            $resultWriter->setFilename($filename);
                        }

                        // Set Outputter for result writers that require it
                        if ($resultWriter instanceof OutputWriterInterface) {
                            $resultWriter->setOutputter($this->output);
                        }

                        if (
                            isset($this->initializedResultWrites[get_class($entity)][get_class($resultWriter)]) === false
                            || $this->initializedResultWrites[get_class($entity)][get_class($resultWriter)] !== true
                        ) {
                            $resultWriter->initializeResultWrites();
                            $this->initializedResultWrites[get_class($entity)][get_class($resultWriter)] = true;
                        }

                        $resultWriter->write($entity);
                    }
                }
            }
        }
    }

    protected function fetchRobotsTxt(Client $client, Url $url)
    {
        if ($this->configuration->getRobotsParser() === null) {
            return false;
        }

        $robotsTxtUrl = $url->getDomain() . '/robots.txt';

        try {
            $response = $client->request('GET', $robotsTxtUrl);
            $this->logWriter->info('GET ' . $robotsTxtUrl);
        } catch (ConnectException $exception) {
            $this->logWriter->critical("GET {$robotsTxtUrl} Could not connect to the server");
            throw $exception;
        }

        $statusCode = new StatusCode($response->getStatusCode());
        if ($statusCode->isProcessable() === false) {
            $this->logWriter->notice("GET {$robotsTxtUrl} Skipped due to unprocesable response code ({$statusCode->getCode()})");
            return false;
        }

        return $response->getBody()->getContents();
    }

    protected function isUrlsLimitReached(): bool
    {
        return $this->crawledUrls >= $this->configuration->getMaxCrawledUrls()
               && $this->configuration->getMaxCrawledUrls() !== 0;
    }
}

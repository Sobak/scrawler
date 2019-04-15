<?php

declare(strict_types=1);

namespace Sobak\Scrawler;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Sobak\Scrawler\Block\ResultWriter\FilenameProvider\FilenameProviderInterface;
use Sobak\Scrawler\Block\ResultWriter\FileResultWriterInterface;
use Sobak\Scrawler\Block\ResultWriter\ResultWriterInterface;
use Sobak\Scrawler\Client\ClientFactory;
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

    protected $initializedResultWrites;

    protected $logWriter;

    protected $output;

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
        $initialUrl = new Url($this->configuration->getBaseUrl());

        $this->makeRequest($client, $initialUrl, []);

        $duration = round(microtime(true) - $timeStart, 2);
        $this->logWriter->notice("Finished in {$duration}s");

        return 0;
    }

    protected function makeRequest(Client $client, Url $url, array $visitedUrls): void
    {
        $this->logWriter->info('GET ' . $url->getUrl());

        $response = $client->request('GET', $url->getUrl());

        $statusCode = new StatusCode($response->getStatusCode());
        if ($statusCode->isProcessable()) {
            $this->processResponse($response);
        } else {
            $this->logWriter->notice("Skipped processing, unprocessable response code: HTTP {$statusCode->getCode()}");
        }

        // Gather list of next URLs using rules specified in configuration
        foreach ($this->configuration->getUrlListProviders() as $urlListProvider) {
            $urlListProvider->setCurrentUrl($url);
            $urlListProvider->setResponse(clone $response);

            $urlList = $urlListProvider->getUrls();

            foreach ($urlList as $url) {
                $urlString = $url->getUrl();

                if (isset($visitedUrls[$urlString]) === false) {
                    $visitedUrls[$urlString] = true;

                    $this->makeRequest($client, $url, $visitedUrls);
                }
            }
        }
    }

    protected function processResponse(ResponseInterface $response): void
    {
        $responseBody = $response->getBody()->getContents();

        foreach ($this->configuration->getObjectDefinitions() as $objectListName => $objectDefinition) {
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

                    $this->logWriter->debug("Matched object to the {$entityClass} entity");

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
}

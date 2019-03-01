<?php

namespace Sobak\Scrawler\Client\Request;

use GuzzleHttp\Client;
use Sobak\Scrawler\Client\Response\Elements\Url;
use Sobak\Scrawler\Configuration\Configuration;
use Sobak\Scrawler\Entity\EntityMapper;
use Sobak\Scrawler\Output\Outputter;
use Sobak\Scrawler\Output\OutputWriterInterface;
use Symfony\Component\DomCrawler\Crawler;

class Request
{
    protected $client;

    protected $configuration;

    protected $outputter;

    public function __construct(Client $client, Configuration $configuration, Outputter $outputter)
    {
        $this->client = $client;
        $this->configuration = $configuration;
        $this->outputter = $outputter;
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function makeRequest(Url $url, array $visitedUrls)
    {
        $response = $this->client->request('GET', $url->getUrl());
        $responseBody = $response->getBody()->getContents();

        foreach ($this->configuration->getObjectDefinitions() as $objectListName => $objectDefinition) {
            $objectDefinition->getMatcher()->setCrawler(new Crawler($responseBody));
            $matchesList = $objectDefinition->serializeValue();

            // Iterate over single found object
            foreach ($matchesList as $match) {
                $objectResult = [];
                foreach ($objectDefinition->getFieldDefinitions() as $fieldName => $fieldDefinition) {
                    $fieldDefinition->getMatcher()->setCrawler(new Crawler($match));

                    $objectResult[$fieldName] = $fieldDefinition->serializeValue();
                }

                // Map object result to entities and write them
                foreach ($objectDefinition->getEntityMappings() as $entityClass) {
                    $entity = EntityMapper::mapResultToEntity($objectResult, $entityClass);

                    $resultWriters = $objectDefinition->getResultWriters();
                    if (isset($resultWriters[$entityClass])) {
                        $resultWriter = $resultWriters[$entityClass];

                        // Set Outputter for result writers that require it
                        if ($resultWriter instanceof OutputWriterInterface) {
                            $resultWriter->setOutputter($this->outputter);
                        }

                        $resultWriter->write($entity);
                    }
                }
            }
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

                    $this->makeRequest($url, $visitedUrls);
                }
            }
        }
    }
}

<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\ResultWriter;

use League\Csv\Writer;
use Sobak\Scrawler\Entity\EntityMapper;

class CsvFileResultWriter extends FileResultWriter
{
    /** @var Writer */
    protected $writer;

    public function __construct(array $configuration = [])
    {
        if ($this->isLeagueCsvInstalled() === false) {
            throw new \Exception('You need to install league/csv in order to use CsvFileResultWriter');
        }

        parent::__construct($configuration);
    }

    public function initializeResultWrites(): void
    {
        $this->writer = Writer::createFromString('');
        $this->writer->setDelimiter($this->configuration['delimiter'] ?? ',');
        $this->writer->setEnclosure($this->configuration['enclosure'] ?? '"');
        $this->writer->setEscape($this->configuration['escape_char'] ?? "\\");

        if (isset($this->configuration['insert_bom']) && $this->configuration['insert_bom'] === true) {
            $this->writer->setOutputBOM(Writer::BOM_UTF8);
        }

        if (empty($this->configuration['headers']) === false) {
            $this->insertRow(array_values($this->configuration['headers']));
        }
    }

    public function write(object $entity): bool
    {
        $entityArray = EntityMapper::entityToArray($entity);

        if (empty($this->configuration['headers']) === false) {
            $entityArray = $this->sortByHeaders($entityArray, $this->configuration['headers']);
        }

        $this->insertRow($entityArray);

        return true;
    }

    protected function insertRow(array $columns): void
    {
        $this->writer->insertOne($columns);

        $this->outputManager->appendToFile($this->getFilename() . '.csv', $this->writer->getContent());
    }

    protected function sortByHeaders(array $entity, array $headers): array
    {
        $result = [];
        foreach (array_keys($headers) as $column) {
            if (isset($entity[$column])) {
                $result[$column] = $entity[$column];
            }
        }

        return $result;
    }

    protected function isLeagueCsvInstalled()
    {
        return class_exists(Writer::class);
    }
}

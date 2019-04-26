<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\ResultWriter;

use Sobak\Scrawler\Entity\EntityMapper;

class CsvFileResultWriter extends FileResultWriter
{
    protected $fileHandle;

    public function initializeResultWrites(): void
    {
        $this->fileHandle = $this->outputter->createFileHandle($this->getFilename() . '.csv', 'w');

        if (isset($this->configuration['insert_bom']) && $this->configuration['insert_bom'] === true) {
            fputs($this->fileHandle, "\xEF\xBB\xBF");
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

        $result = $this->insertRow($entityArray);

        if ($result === false) {
            return false;
        }

        return true;
    }

    protected function insertRow(array $columns)
    {
        return fputcsv(
            $this->fileHandle,
            $columns,
            $this->configuration['delimiter'] ?? ',',
            $this->configuration['enclosure'] ?? '"',
            $this->configuration['escape_char'] ?? "\\"
        );
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
}

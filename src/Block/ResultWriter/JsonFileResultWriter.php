<?php

namespace Sobak\Scrawler\Block\ResultWriter;

use Sobak\Scrawler\Entity\EntityInterface;
use Sobak\Scrawler\Output\Outputter;
use Sobak\Scrawler\Output\OutputWriterInterface;

class JsonFileResultWriter extends AbstractResultWriter implements OutputWriterInterface
{
    /** @var Outputter */
    protected $outputter;

    public function getOutputter()
    {
        return $this->outputter;
    }

    public function setOutputter(Outputter $outputter)
    {
        $this->outputter = $outputter;
    }

    public function write(EntityInterface $entity): bool
    {
        $output = json_encode($entity->toArray(), JSON_PRETTY_PRINT);

        $this->outputter->writeToFile(uniqid() . '.json', $output);

        return true;
    }
}

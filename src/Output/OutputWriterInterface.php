<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Output;

/**
 * Interface OutputWriterInterface
 *
 * This interface can be used for various objects to mark them as using OutputManager
 * class for their operations. Scrawler will then inject OutputManager object into them.
 */
interface OutputWriterInterface
{
    /**
     * Returns the output manager instance.
     *
     * @return OutputManagerInterface
     */
    public function getOutputManager(): OutputManagerInterface;

    /**
     * Sets the output manager instance.
     *
     * @param OutputManagerInterface $outputManager
     */
    public function setOutputManager(OutputManagerInterface $outputManager): void;
}

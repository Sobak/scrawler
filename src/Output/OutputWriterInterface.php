<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Output;

/**
 * Interface OutputWriterInterface
 *
 * This interface can be used for various objects to mark them as using Outputter
 * class for their operations. Scrawler will then inject Outputter object into them.
 */
interface OutputWriterInterface
{
    /**
     * Returns the outputter instance.
     *
     * @return Outputter
     */
    public function getOutputter(): Outputter;

    /**
     * Sets the outputter instance.
     *
     * @param Outputter $outputter
     */
    public function setOutputter(Outputter $outputter): void;
}

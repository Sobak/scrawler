<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\ResultWriter\FilenameProvider;

use Exception;

class EntityPropertyFilenameProvider extends AbstractFilenameProvider
{
    public function __construct(array $configuration = [])
    {
        if (isset($configuration['property']) === false) {
            throw new Exception("You must set the 'property' configuration key");
        }

        parent::__construct($configuration);
    }

    public function generateFilename(object $entity): string
    {
        $methodName = 'get' . ucfirst($this->configuration['property']);

        if (method_exists($entity, $methodName) === false) {
            $classname = get_class($entity);
            throw new Exception("$classname::$methodName() must be defined for FilenameProvider to use");
        }

        return $entity->$methodName();
    }
}

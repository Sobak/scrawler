<?php

namespace Sobak\Scrawler\Entity;

class EntityMapper
{
    // @todo typehint
    public function mapResultsToEntity($results, string $entityName): object
    {
        $entity = new $entityName();

        foreach ($results as $key => $value) {
            if (method_exists($entity, $methodName = 'set' . ucfirst($key))) {
                $entity->$methodName($value);
            }
        }

        return $entity;
    }
}

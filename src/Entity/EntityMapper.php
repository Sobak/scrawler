<?php

namespace Sobak\Scrawler\Entity;

class EntityMapper
{
    public static function mapResultToEntity(array $result, string $entityName): object
    {
        $entity = new $entityName();

        foreach ($result as $key => $value) {
            if (method_exists($entity, $methodName = 'set' . ucfirst($key))) {
                $entity->$methodName($value);
            }
        }

        return $entity;
    }
}

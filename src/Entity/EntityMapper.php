<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Entity;

use ReflectionClass;
use ReflectionMethod;

class EntityMapper
{
    public static function entityToArray(object $entity)
    {
        $result = [];
        $class = new ReflectionClass(get_class($entity));

        foreach ($class->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            $methodName = $method->name;
            if (strpos($methodName, 'get') === 0 && strlen($methodName) > 3) {
                $propertyName = lcfirst(substr($methodName, 3));
                $value = $method->invoke($entity);

                $result[$propertyName] = $value;
            }
        }

        return $result;
    }

    public static function resultToEntity(array $result, string $entityName): object
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

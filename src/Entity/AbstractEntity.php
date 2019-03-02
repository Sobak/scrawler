<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Entity;

use ReflectionClass;
use ReflectionMethod;

abstract class AbstractEntity implements EntityInterface
{
    public function toArray(): array
    {
        $result = [];
        $class = new ReflectionClass(get_class($this));

        foreach ($class->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            $methodName = $method->name;
            if (strpos($methodName, 'get') === 0 && strlen($methodName) > 3) {
                $propertyName = lcfirst(substr($methodName, 3));
                $value = $method->invoke($this);

                $result[$propertyName] = $value;
            }
        }

        return $result;
    }
}

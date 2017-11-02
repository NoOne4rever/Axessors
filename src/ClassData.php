<?php

namespace Axessors;

use Axessors\Exceptions\InternalError;

class ClassData
{
    public $reflection;

    private $properties = [];

    public function __construct(\ReflectionClass $reflection)
    {
        $this->reflection = $reflection;
    }

    public function addProperty(string $name, PropertyData $propertyData)
    {
        $this->properties[$name] = $propertyData;
    }

    public function getProperty(string $name): PropertyData
    {
        if (isset($this->properties[$name])) {
            return $this->properties[$name];
        } else {
            throw new InternalError("property {$this->reflection->name}::\$$name not found in Axessors\\ClassData");
        }
    }

    public function getAllProperties(): array
    {
        return $this->properties;
    }

    public function getAllMethods(bool $skipPrivate = false): array
    {
        $methods = [];
        foreach ($this->properties as $propertyData) {
            $propertyMethods = $propertyData->getMethods();
            if ($skipPrivate) {
                unset($propertyMethods['private']);
            }
            $methods = array_merge_recursive($methods, $propertyMethods);
        }
        return $methods;
    }
}

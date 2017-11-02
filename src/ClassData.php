<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @package Axessors
 * @license GPL
 */

namespace Axessors;

use Axessors\Exceptions\InternalError;

/**
 * Class ClassData.
 * 
 * Stores the reflection of a class and Axessors properties.
 */
class ClassData
{
    /** @var \ReflectionClass class data */
    public $reflection;
    
    /** @var PropertyData[] Axessors properties */
    private $properties = [];

    /**
     * ClassData constructor.
     * 
     * @param \ReflectionClass $reflection reflection of the class
     */
    public function __construct(\ReflectionClass $reflection)
    {
        $this->reflection = $reflection;
    }

    /**
     * Adds property information to class data.
     * 
     * @param string $name name of the property
     * @param PropertyData $propertyData property data
     */
    public function addProperty(string $name, PropertyData $propertyData)
    {
        $this->properties[$name] = $propertyData;
    }

    /**
     * Returns property by name.
     * 
     * @param string $name property name
     * @return PropertyData information about property
     * @throws InternalError if property not found
     */
    public function getProperty(string $name): PropertyData
    {
        if (isset($this->properties[$name])) {
            return $this->properties[$name];
        } else {
            throw new InternalError("property {$this->reflection->name}::\$$name not found in Axessors\\ClassData");
        }
    }

    /**
     * Returns all Axessors properties.
     * 
     * @return PropertyData[] class' properties
     */
    public function getAllProperties(): array
    {
        return $this->properties;
    }

    /**
     * Returns all Axessors methods names.
     * 
     * @param bool $skipPrivate a flag; indicates if private methods are skipped 
     * @return string[] Axessors methods' names
     */
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

<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors;

use NoOne4rever\Axessors\Exceptions\InternalError;

/**
 * Class MethodsProcessor.
 *
 * Processes Axessors methods.
 *
 * @package NoOne4rever\Axessors
 */
class MethodsProcessor
{
    /** @var string access modifier for setter */
    private $writingAccess;
    /** @var string access modifier for getter */
    private $readingAccess;
    /** @var string field name */
    private $name;
    /** @var array generated methods */
    private $methods = [];

    /**
     * MethodsProcessor constructor.
     * 
     * @param string $writingAccess access modifier for setter
     * @param string $readingAccess access modifier for getter
     * @param string $name field name
     */
    public function __construct(string $writingAccess, string $readingAccess, string $name)
    {
        $this->name = $name;
        $this->writingAccess = $writingAccess;
        $this->readingAccess = $readingAccess;
    }

    /**
     * Generates list of methods for property.
     *
     * @param array $typeTree type tree
     *
     * @return string[] methods' names
     */
    public function processMethods(array $typeTree): array
    {
        $this->methods = [];
        $this->processAccessors();
        foreach ($typeTree as $index => $type) {
            $this->addMethods(is_int($index) ? $type : $index);
        }
        return $this->methods;
    }

    /**
     * Adds Axessors methods from type to methods list.
     * 
     * @param string $type class name
     */
    private function addMethods(string $type): void
    {
        foreach ((new \ReflectionClass($type))->getMethods() as $method) {
            if (!($method->isStatic() && $method->isPublic() && !$method->isAbstract())) {
                continue;
            }
            $this->processAxessorsMethod($method);
        }
    }

    /**
     * Adds getter and setter to methods list.
     */
    private function processAccessors(): void
    {
        if ($this->readingAccess !== '') {
            $this->methods[$this->readingAccess][] = 'get' . ucfirst($this->name);
        }
        if ($this->writingAccess !== '') {
            $this->methods[$this->writingAccess][] = 'set' . ucfirst($this->name);
        }
    }

    /**
     * Adds Axessors method to methods list.
     * 
     * @param \ReflectionMethod $method reflection
     */
    private function processAxessorsMethod(\ReflectionMethod $method): void
    {
        if (substr($method->name, 0, 5) == 'm_in_' && $this->writingAccess !== '') {
            $this->methods[$this->writingAccess][] = str_replace('PROPERTY', ucfirst($this->name),
                substr($method->name, 5));
        } elseif (substr($method->name, 0, 6) == 'm_out_' && $this->readingAccess !== '') {
            $this->methods[$this->readingAccess][] = str_replace('PROPERTY', ucfirst($this->name),
                substr($method->name, 6));
        }
    }
}
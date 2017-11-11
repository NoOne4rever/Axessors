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
    private $writingAccess;
    private $readingAccess;
    private $name;
    private $methods = [];

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
            $class = is_int($index) ? $type : $index;
            foreach ((new \ReflectionClass($class))->getMethods() as $method) {
                if (!($method->isStatic() && $method->isPublic() && !$method->isAbstract())) {
                    continue;
                }
                $this->processAxessorsMethod($method);
            }
        }
        return $this->methods;
    }

    private function processAccessors(): void
    {
        if ($this->readingAccess !== '') {
            $this->methods[$this->readingAccess][] = 'get' . ucfirst($this->name);
        }
        if ($this->writingAccess !== '') {
            $this->methods[$this->writingAccess][] = 'set' . ucfirst($this->name);
        }
    }

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
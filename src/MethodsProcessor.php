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
        $methods = [];

        if ($this->readingAccess !== '') {
            $methods[$this->readingAccess][] = 'get' . ucfirst($this->name);
        }
        if ($this->writingAccess !== '') {
            $methods[$this->writingAccess][] = 'set' . ucfirst($this->name);
        }

        foreach ($typeTree as $index => $type) {
            $class = is_int($index) ? $type : $index;
            try {
                class_exists($class);
            } catch (InternalError $error) {
                continue;
            }
            foreach ((new \ReflectionClass($class))->getMethods() as $method) {
                $isAccessible = $method->isStatic() && $method->isPublic() && !$method->isAbstract();
                if ($isAccessible && preg_match('{^m_(in|out)_.*?PROPERTY.*}', $method->name)) {
                    if (substr($method->name, 0, 5) == 'm_in_' && $this->writingAccess !== '') {
                        $methods[$this->writingAccess][] = str_replace('PROPERTY', ucfirst($this->name),
                            substr($method->name, 5));
                    } elseif (substr($method->name, 0, 6) == 'm_out_' && $this->readingAccess !== '') {
                        $methods[$this->readingAccess][] = str_replace('PROPERTY', ucfirst($this->name),
                            substr($method->name, 6));
                    }
                }
            }
        }
        return $methods;
    }
}
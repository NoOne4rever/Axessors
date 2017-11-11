<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors;

use NoOne4rever\Axessors\Exceptions\AxessorsError;
use NoOne4rever\Axessors\Exceptions\InternalError;
use NoOne4rever\Axessors\Exceptions\OopError;
use NoOne4rever\Axessors\Exceptions\TypeError;

/**
 * Class CallProcessor
 *
 * Handles method call.
 *
 * @package NoOne4rever\Axessors
 */
class CallProcessor
{
    /** @var string method name */
    private $method;
    /** @var mixed|null an object */
    private $object;
    /** @var string class name */
    private $class;
    private $line;
    private $file;
    private $callingClass;
    /** @var \ReflectionClass class data */
    private $reflection;

    /**
     * CallProcessor constructor.
     *
     * @param array $backtrace contains call stack
     * @param mixed|null $object an object the method is called on (might not be given, if the method is static)
     */
    public function __construct(array $backtrace, $object = null)
    {
        $this->object = $object;
        $this->class = $backtrace[0]['class'];
        $this->line = $backtrace[1]['line'];
        $this->file = $backtrace[1]['file'];
        $this->callingClass = $backtrace[1]['class'];
    }

    /**
     * General function of calling the method.
     *
     * Processes method's name to extract property name and checks if the method is accessible.
     *
     * @param array $args the arguments of the method called
     * @param string $method the name of the method called
     * @return mixed return value of the method called
     * @throws AxessorsError is thrown when Axessors method not found
     * @throws OopError is thrown when the method is not accessible
     */
    public function call(array $args, string $method)
    {
        $data = Data::getInstance();
        $classData = $data->getClass($this->class);
        $this->method = $method;
        $this->reflection = $classData->reflection;
        $propertyData = $this->searchMethod($classData, $data);
        $runner = new MethodRunner(0, $propertyData, $this->class, $this->method, $this->object);
        return $runner->run($args);
    }

    private function searchMethod(ClassData $classData, Data $data): PropertyData
    {
        while (!is_null($classData)) {
            foreach ($classData->getAllProperties() as $propertyData) {
                foreach ($propertyData->getMethods() as $modifier => $methods) {
                    foreach ($methods as $method) {
                        if ($this->method === $method) {
                            if ($this->isAccessible($modifier, $classData->reflection)) {
                                return $propertyData;
                            } else {
                                throw new OopError("method $this->class::$this->method() is not accessible there");
                            }
                        }
                    }
                }
            }
            $classData = $this->getParentClass($classData, $data);
        }
        throw new AxessorsError("method {$this->class}::{$this->method}() not found");
    }

    private function getParentClass(ClassData $classData, Data $data): ?ClassData
    {
        $reflection = $classData->reflection->getParentClass();
        if ($reflection === false) {
            //throw new InternalError('no parent class found');
            return null;
        }
        try {
            return $data->getClass($reflection->name);
        } catch (InternalError $error) {
            return null;
        }
    }

    /**
     * Checks if the method is accessible.
     *
     * @param string $accessModifier access modifier
     * @param \ReflectionClass $reflection class data
     * @return bool result of the checkout
     * @throws InternalError if not a valid it's impossible to check accessibility.
     */
    private function isAccessible(string $accessModifier, \ReflectionClass $reflection): bool
    {
        if ($accessModifier === 'public') {
            return true;
        }
        $isCalledClass = $this->callingClass === $reflection->name;
        $inCalledClass = $this->file === $reflection->getFileName() && $this->in($reflection);
        if ($accessModifier === 'private') {
            return $isCalledClass && $inCalledClass;
        }
        $isSubclass = is_subclass_of($this->callingClass, $reflection->name);
        $reflection = new \ReflectionClass($this->callingClass);
        $inSubclass = $this->file === $reflection->getFileName() && $this->in($reflection);
        return ($isSubclass && $inSubclass) || ($isCalledClass && $inCalledClass); 
    }

    /**
     * Checks if the method called in right place and it is accessible there.
     *
     * @param \ReflectionClass $reflection class data
     * @return bool result of the checkout
     */
    private function in(\ReflectionClass $reflection): bool
    {
        return $reflection->getStartLine() <= $this->line && $reflection->getEndLine() >= $this->line;
    }
}

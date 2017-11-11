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
    /** @var mixed call stack */
    private $backtrace;
    /** @var string class name */
    private $class;
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
        $this->backtrace = $backtrace[count($backtrace) - 1];
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
        if ($accessModifier == 'public') {
            return true;
        }
        $isThis = $reflection->name == $this->backtrace['class'];
        $inThisFile = $this->backtrace['file'] == $reflection->getFileName() && $this->in($reflection);
        if ($accessModifier == 'private') {
            return $isThis && $inThisFile;
        }
        $isThisBranch = is_subclass_of($this->backtrace['class'], $reflection->name) || is_subclass_of($reflection->name,
                $this->backtrace['class']);
        $reflection = new \ReflectionClass($this->class);
        $inBranchFile = $this->backtrace['file'] == $reflection->getFileName() && $this->in(new \ReflectionClass($this->backtrace['class']));
        if ($accessModifier == 'protected') {
            return ($isThis && $inThisFile) || ($isThis && $inBranchFile) || ($isThisBranch && $inBranchFile);
        }
        throw new InternalError('not a valid access modifier given');
    }

    /**
     * Checks if the method called in right place and it is accessible there.
     *
     * @param \ReflectionClass $reflection class data
     * @return bool result of the checkout
     */
    private function in(\ReflectionClass $reflection): bool
    {
        return $reflection->getStartLine() <= $this->backtrace['line'] && $reflection->getEndLine() >= $this->backtrace['line'];
    }
}

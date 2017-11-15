<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors;

use NoOne4rever\Axessors\Exceptions\{
    AxessorsError,
    InternalError,
    OopError
};

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
    /** @var int method call line number */
    private $line;
    /** @var string method call file name */
    private $file;
    /** @var string the name of calling class */
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
        for ($i = 1; ; ++$i) {
            if (isset($backtrace[$i]['line'])) {
                $this->line = $backtrace[$i]['line'];
                $this->file = $backtrace[$i]['file'];
                break;
            }
        }
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
        $classData = Data::getClass($this->class);
        $this->method = $method;
        $this->reflection = $classData->reflection;
        $propertyData = $this->searchMethod($classData);
        $runner = new MethodRunner(0, $propertyData, $this->class, $this->method, $this->object);
        return $runner->run($args, $this->file, $this->line);
    }

    /**
     * Searches called method in Axessors methods.
     * 
     * @param ClassData $classData class data
     * @return PropertyData property that has the called method
     * @throws AxessorsError if the called method not found
     * @throws OopError if the called method is not accessible
     */
    private function searchMethod(ClassData $classData): PropertyData
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
            $classData = $this->getParentClass($classData);
        }
        throw new AxessorsError("method {$this->class}::{$this->method}() not found");
    }

    /**
     * Returns parent class data.
     * 
     * @param ClassData $classData class data
     * @return ClassData|null parent class
     */
    private function getParentClass(ClassData $classData): ?ClassData
    {
        $reflection = $classData->reflection->getParentClass();
        if ($reflection === false) {
            return null;
        }
        try {
            return Data::getClass($reflection->name);
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
        switch ($accessModifier) {
            case 'public':
                return true;
            case 'protected':
                return $this->isAccessibleProtected($reflection);
            case 'private':
                return $this->isAccessiblePrivate($reflection);
        }
        throw new InternalError('not a valid access modifier given');
    }

    /**
     * Checks if a private method is accessible.
     * 
     * @param \ReflectionClass $reflection reflection
     * @return bool the result of the checkout
     */
    private function isAccessiblePrivate(\ReflectionClass $reflection): bool 
    {
        $isCalledClass = $this->callingClass === $reflection->name;
        $inCalledClass = $this->file === $reflection->getFileName() && $this->in($reflection);
        return $isCalledClass && $inCalledClass;
    }

    /**
     * Checks if a protected method is accessible.
     * 
     * @param \ReflectionClass $reflection reflection
     * @return bool the result of the checkout
     */
    private function isAccessibleProtected(\ReflectionClass $reflection): bool 
    {
        $isSubclass = is_subclass_of($this->callingClass, $reflection->name);
        $reflection = new \ReflectionClass($this->callingClass);
        $inSubclass = $this->file === $reflection->getFileName() && $this->in($reflection);
        return ($isSubclass && $inSubclass) || $this->isAccessiblePrivate($reflection);
    }

    /**
     * Checks if the method is called in right place and it is accessible there.
     *
     * @param \ReflectionClass $reflection class data
     * @return bool result of the checkout
     */
    private function in(\ReflectionClass $reflection): bool
    {
        return $reflection->getStartLine() <= $this->line && $reflection->getEndLine() >= $this->line;
    }
}

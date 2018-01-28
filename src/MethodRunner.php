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
    TypeError
};

/**
 * Class MethodRunner.
 *
 * Runs a method generated by Axessors.
 *
 * @package NoOne4rever\Axessors
 */
class MethodRunner extends RunningSuit
{
    /** @var string method name */
    private $method;

    /**
     * MethodRunner constructor.
     *
     * @param int $mode running mode
     * @param PropertyData $data property data
     * @param string $class class
     * @param string $method method name
     * @param object|null $object object
     */
    public function __construct(int $mode, PropertyData $data, string $class, string $method, $object = null)
    {
        parent::__construct($mode, $data, $class, $object);
        $this->method = $method;
    }

    /**
     * Emulates execution of the method.
     *
     * @param array $args the arguments of the method called
     * @param string $file filename
     * @param int $line line number
     * 
     * @return mixed return value of the method called
     * @throws AxessorsError if conditions for executing an accessor did not pass
     * @throws AxessorsError if Axessors method not found
     */
    public function run(array $args, string $file, int $line)
    {
        $prefix = substr($this->method, 0, 3);
        if ('get' . ucfirst($this->propertyData->getAlias()) === $this->method) {
            $this->mode = RunningSuit::OUTPUT_MODE;
            $this->propertyData->reflection->setAccessible(true);
            $value = is_null($this->object) ? $this->propertyData->reflection->getValue() : $this->propertyData->reflection->getValue($this->object);
            $this->propertyData->reflection->setAccessible(false);
            return $this->executeAccessor(RunningSuit::OUTPUT_MODE, $value);
        } elseif ('set' . ucfirst($this->propertyData->getAlias()) === $this->method) {
            $this->mode = RunningSuit::INPUT_MODE;
            if (!isset($args[0])) {
                throw new AxessorsError("setter could not be called without arguments at $file:$line");
            }
            $value = $this->executeAccessor(RunningSuit::INPUT_MODE, $args[0]);
            $this->propertyData->reflection->setAccessible(true);
            is_null($this->object) ? $this->propertyData->reflection->setValue($value) : $this->propertyData->reflection->setValue($this->object,
                $value);
            $this->propertyData->reflection->setAccessible(false);
            return;
        } else {
            if ($this->propertyData->getAlias() === '') {
                $this->method .= 'PROPERTY';
            } else {
                $this->method = str_replace(ucfirst($this->propertyData->getAlias()), 'PROPERTY', $this->method);
            }
            return $this->runAxessorsMethod($args);
        }
    }

    /**
     * Searches and runs Axessors method.
     *
     * @param array $args method parameters
     * @return mixed result of function
     * @throws AxessorsError if requested method not found
     */
    private function runAxessorsMethod(array $args)
    {
        $this->propertyData->reflection->setAccessible(true);
        $value = $this->propertyData->reflection->getValue($this->object);
        $this->checkType($this->propertyData->getTypeTree(), $value, RunningSuit::OUTPUT_MODE);
        foreach ($this->propertyData->getTypeTree() as $type => $subType) {
            $type = is_int($type) ? $subType : $type;
            $reflection = new \ReflectionClass($type);
            foreach ($reflection->getMethods() as $method) {
                if ($this->isCalledAxessorsMethod($method)) {
                    return $this->executeAxessorsMethod($type, $method->name, $value, $args);
                }
            }
        }
        throw new AxessorsError("method {$this->class}::{$this->method}() not found");
    }

    /**
     * Checks if the method given is called method.
     * 
     * @param \ReflectionMethod $method method
     * @return bool the result of the checkout
     */
    private function isCalledAxessorsMethod(\ReflectionMethod $method): bool 
    {
        $isAccessible = $method->isStatic() && $method->isPublic() && !$method->isAbstract();
        $isCalled = $method->name === "m_in_$this->method" || $method->name === "m_out_$this->method";
        return $isAccessible && $isCalled;
    }
    
    /**
     * Executes Axessors method.
     *
     * @param string $type type name
     * @param string $name method name
     * @param mixed $value value to read/write
     * @param array $args arguments
     * @return mixed function result
     */
    private function executeAxessorsMethod(string $type, string $name, $value, array $args)
    {
        if ($name == "m_in_$this->method") {
            // add support for static properties
            $this->propertyData->reflection->setValue($this->object,
                call_user_func([$type, "m_in_$this->method"], $value, $args));
            $this->propertyData->reflection->setAccessible(false);
            return;
        } elseif ($name == "m_out_$this->method") {
            $result = call_user_func([$type, "m_out_$this->method"], $value, $args);
            $this->propertyData->reflection->setAccessible(false);
            return $result;
        }
    }

    /**
     * Executes complex accessor.
     *
     * @param int $mode running mode
     * @param mixed $value field or argument value
     * @return mixed new value
     * @throws AxessorsError if conditions for accessor did not pass
     */
    private function executeAccessor(int $mode, $value)
    {
        $this->checkType($this->propertyData->getTypeTree(), $value);
        $conditionsSuit = new ConditionsRunner($mode, $this->propertyData, $this->class, $this->method, $this->object);
        if ($conditionsSuit->processConditions($value)) {
            $handlersSuit = new HandlersRunner($mode, $this->propertyData, $this->class, $this->object);
            $value = $handlersSuit->executeHandlers($value);
            return $value;
        } else {
            throw new AxessorsError("conditions for {$this->class}::{$this->method}() did not pass");
        }
    }

    /**
     * Checks if the variable belongs to defined type.
     * 
     * @param mixed $var variable
     * @param string $type type
     * @return bool the result of the checkout
     */
    private function is($var, string $type): bool
    {
        $isExactClass = $var instanceof $type;
        $isAxessorsType = ((new \ReflectionClass($type))->hasMethod('is') && call_user_func([$type, 'is'], $var));
        return $isExactClass || $isAxessorsType;
    }

    /**
     * Checks if the type of new property's value is correct.
     *
     * @param array $typeTree all possible types
     * @param $var mixed new value of the property
     * @throws TypeError if the type of new property's value does not match the type defined in Axessors comment
     */
    private function checkType(array $typeTree, $var, $mode = null): void
    {
        if (($mode ?? $this->mode) == RunningSuit::OUTPUT_MODE && is_null($var)) {
            return;
        }
        foreach ($typeTree as $type => $subType) {
            if (is_int($type) && $this->is($var, $subType)) {
                return;
            } elseif (is_iterable($var) && $this->is($var, $type)) {
                foreach ($var as $subVar) {
                    $this->checkType($subType, $subVar);
                }
                return;
            }
        }
        throw new TypeError("not a valid type of {$this->class}::\${$this->propertyData->getName()}");
    }
}
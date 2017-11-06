<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @package NoOne4rever\Axessors
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
 */
class CallProcessor
{
    /** @var string method name */
    private $method;
    /** @var mixed|null an object */
    private $object;
    /** @var mixed call stack */
    private $backtrace;
    /** @var bool accessor mode */
    private $mode;
    /** @var string class name */
    private $class;
    /** @var PropertyData property data */
    private $propertyData;
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
        $this->class = $backtrace[1]['class'] ?? $backtrace[0]['class'];
        $this->backtrace = $backtrace[0];
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
        $classData = $data->getClass($this->backtrace['class']);
        $this->method = $method;
        $this->reflection = $classData->reflection;
        while (true) {
            foreach ($classData->getAllProperties() as $propertyData) {
                foreach ($propertyData->getMethods() as $accessModifier => $methods) {
                    foreach ($methods as $method) {
                        if ($this->method == $method) {
                            if ($this->isAccessible($accessModifier, $classData->reflection)) {
                                return $this->run($propertyData, $args);
                            } else {
                                throw new OopError("method {$this->backtrace['class']}::{$this->method}() is not accessible there");
                            }
                        }
                    }
                }
            }
            $reflection = $classData->reflection->getParentClass();
            if ($reflection === false) {
                break;
            }
            try {
                $classData = $data->getClass($reflection->name);
            } catch (InternalError $error) {
                break;
            }
        }
        throw new AxessorsError("method {$this->backtrace['class']}::{$this->method}() not found");
    }

    /**
     * Emulates execution of the method.
     * 
     * @param PropertyData $propertyData data of the property
     * @param array $args the arguments of the method called
     * @return mixed return value of the method called
     * @throws AxessorsError if conditions for executing an accessor did not pass
     * @throws AxessorsError if Axessors method not found
     */
    private function run(PropertyData $propertyData, array $args)
    {
        $prefix = substr($this->method, 0, 3);
        $this->propertyData = $propertyData;
        if ($prefix == 'get') {
            $this->mode = true;
            $propertyData->reflection->setAccessible(true);
            $value = is_null($this->object) ? $propertyData->reflection->getValue() : $propertyData->reflection->getValue($this->object);
            $this->checkType($this->propertyData->getTypeTree(), $value);
            $propertyData->reflection->setAccessible(false);
            if ($this->processConditions($value)) {
                $value = $this->executeHandlers($value);
                return $value;
            } else {
                throw new AxessorsError("conditions for {$this->backtrace['class']}::{$this->method}() did not pass");
            }
        } elseif ($prefix == 'set') {
            $this->mode = false;
            $this->checkType($this->propertyData->getTypeTree(), $args[0]);
            if ($this->processConditions($args[0])) {
                $propertyData->reflection->setAccessible(true);
                $value = $this->executeHandlers($args[0]);
                is_null($this->object) ? $propertyData->reflection->setValue($value) : $propertyData->reflection->setValue($this->object,
                    $value);
                $propertyData->reflection->setAccessible(false);
                return;
            } else {
                throw new AxessorsError("conditions for {$this->backtrace['class']}::{$this->method}() did not pass");
            }
        } else {
            $this->method = str_replace(ucfirst($this->propertyData->getName()), 'PROPERTY', $this->method);
            foreach ($this->propertyData->getTypeTree() as $type => $subType) {
                $type = is_int($type) ? $subType : $type;
                $reflection = new \ReflectionClass($type);
                foreach ($reflection->getMethods() as $method) {
                    if (preg_match('{^m_(in|out)_}',
                            $method->name) && $method->isStatic() && $method->isPublic() && !$method->isAbstract()) {
                        if ($method->name == "m_in_$this->method") {
                            $propertyData->reflection->setAccessible(true);
                            $value = $propertyData->reflection->getValue($this->object);
                            $this->checkType($this->propertyData->getTypeTree(), $value);
                            // add support for static properties
                            $propertyData->reflection->setValue(
                                $this->object, call_user_func(
                                    [$type, "m_in_$this->method"], $propertyData->reflection->getValue(
                                    $this->object
                                ), $args
                                )
                            );
                            $propertyData->reflection->setAccessible(false);
                            return;
                        } elseif ($method->name == "m_out_$this->method") {
                            $propertyData->reflection->setAccessible(true);
                            $value = $propertyData->reflection->getValue($this->object);
                            $this->checkType($this->propertyData->getTypeTree(), $value);
                            $result = call_user_func([$type, "m_out_$this->method"], $value, $args);
                            $propertyData->reflection->setAccessible(false);
                            return $result;
                        }
                    }
                }
            }
            throw new AxessorsError("method {$this->backtrace['class']}::{$this->method}() not found");
        }
    }

    /**
     * Checks if the type of new property's value is correct.
     * 
     * @param array $typeTree all possible types
     * @param $var mixed new value of the property
     * @throws TypeError if the type of new property's value does not match the type defined in Axessors comment
     */
    private function checkType(array $typeTree, $var): void
    {
        foreach ($typeTree as $type => $subType) {
            if (is_int($type)) {
                if ($var instanceof $subType || ((new \ReflectionClass($subType))->hasMethod('is') && call_user_func([
                            $subType,
                            'is'
                        ], $var))) {
                    return;
                }
            } else {
                if (is_iterable($var) && ($var instanceof $type || ((new \ReflectionClass($type))->hasMethod('is') && call_user_func([
                                $type,
                                'is'
                            ], $var)))) {
                    foreach ($var as $subVar) {
                        $this->checkType($subType, $subVar);
                    }
                    return;
                }
            }
        }
        throw new TypeError("not a valid type of {$this->backtrace['class']}::\${$this->propertyData->getName()}");
    }

    /**
     * Executes handlers defined in the Axessors comment.
     * 
     * @param $value mixed value of the property
     * @return mixed new value of the property
     * @throws OopError if the property does not have one of the handlers defined in the Axessors comment
     */
    private function executeHandlers($value)
    {
        $handlers = $this->mode ? $this->propertyData->getOutputHandlers() : $this->propertyData->getInputHandlers();
        foreach ($handlers as $handler) {
            if (strpos($handler, '`') !== false) {
                //$handler = trim($handler, '`');
                $handler = str_replace('\\`', '`', substr($handler, 1, strlen($handler) - 2));
                if (is_null($this->object)) {
                    $value = call_user_func([$this->reflection->name, '__axessors_execute_static'], $handler, $value,
                        false);
                } else {
                    $value = call_user_func([$this->object, '__axessors_execute_instance'], $handler, $value, false);
                }
            } else {
                foreach ($this->propertyData->getTypeTree() as $type => $subType) {
                    $reflection = new \ReflectionClass('\Axessors\Types\\' . is_int($type) ? $subType : $type);
                    foreach ($reflection->getMethods() as $method) {
                        $isAccessible = $method->isPublic() && $method->isStatic() && !$method->isAbstract();
                        $isThat = call_user_func([$reflection->name, 'is'], $value);
                        if ($isAccessible && $isThat && "h_$handler" == $method->name) {
                            $value = call_user_func([$reflection->name, $method->name], $value, false);
                            continue 3;
                        } elseif ($isAccessible && "h_$handler" == $method->name && !$isThat) {
                            continue 3;
                        }
                    }
                }
                throw new OopError("property {$this->backtrace['class']}::\${$this->propertyData->getName()} does not have handler \"$handler\"");
            }
        }
        return $value;
    }

    /**
     * Checks the conditions defined in the Axessors comment.
     * 
     * Creates logical tree of the conditions and then checks if the general result is true.
     * 
     * @param $value mixed value of the property
     * @return bool result of checking of the conditions
     */
    private function processConditions($value): bool
    {
        $conditions = $this->calculateConditions($value);
        if (empty($conditions)) {
            return true;
        }
        foreach ($conditions as $condition) {
            if (is_array($condition)) {
                foreach ($condition as $subCondition) {
                    if (!$subCondition) {
                        continue 2;
                    }
                }
                return true;
            }
            if ($condition) {
                return true;
            }
        }
        return false;
    }

    /**
     * Calculates every condition defined in the Axessors comment.
     * 
     * @param $value mixed value of the property
     * @return bool[] results of the conditions
     */
    private function calculateConditions($value): array
    {
        $calculatedConditions = [];
        $conditions = $this->mode ? $this->propertyData->getOutputConditions() : $this->propertyData->getInputConditions();
        foreach ($conditions as $number => $complexCondition) {
            if (is_array($complexCondition)) {
                foreach ($complexCondition as $condition) {
                    $calculatedConditions[$number][] = $this->executeCondition($condition, $value);
                }
            } else {
                $calculatedConditions[$number] = $this->executeCondition($complexCondition, $value);
            }
        }
        return $calculatedConditions;
    }

    /**
     * Calculates a condition defined in the Axessors comment.
     * 
     * @param $condition string the condition
     * @param $value mixed value of the property
     * @return bool result of the condition
     */
    private function executeCondition(string $condition, $value): bool
    {
        if (strpos($condition, '`') !== false) {
            $condition = str_replace('\\`', '`', substr($condition, 1, strlen($condition) - 2));
            if (is_null($this->object)) {
                return call_user_func([$this->backtrace['class'], '__axessors_execute_static'], $condition, $value,
                    true);
            } else {
                return call_user_func([$this->object, '__axessors_execute_instance'], $condition, $value, true);
            }
        } else {
            $value = $this->count($value);
            if (strpos($condition, '..') !== false) {
                $condition = explode('..', $condition);
                $condition = "<= {$condition[1]} && $value >= {$condition[0]}";
            }
            return eval("return $value $condition;");
        }
    }

    /**
     * Casts the property to integer.
     * 
     * If the property is string or array returns it's length.
     * If the property is integer of float returns the property itself.
     * 
     * @param $value mixed value of the property
     * @return int integer value of the property
     * @throws TypeError if the property can't be turned into integer
     */
    private function count($value): int
    {
        switch (gettype($value)) {
            case 'integer':
            case 'float':
                break;
            case 'string':
                $value = strlen($value);
                break;
            case 'array':
                $value = count($value);
                break;
            default:
                throw new TypeError('value "' . var_export($value) . "\" passed to {$this->backtrace['class']}::{$this->method}() is not countable");
        }
        return $value;
    }

    /**
     * Checks if the method is accessible.
     * 
     * @param string $accessModifier access modifier
     * @param \ReflectionClass $reflection class data
     * @return bool result of the checkout
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
        $isThisBranch = is_subclass_of($this->class, $reflection->name) || is_subclass_of($reflection->name,
                $this->class);
        $reflection = new \ReflectionClass($this->class);
        $inBranchFile = $this->backtrace['file'] == $reflection->getFileName() && $this->in($reflection);
        if ($accessModifier == 'protected') {
            return ($isThis && $inThisFile) || ($isThis && $inBranchFile) || ($isThisBranch && $inBranchFile);
        }
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

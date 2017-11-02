<?php

namespace Axessors;

use Axessors\Exceptions\AxessorsError;
use Axessors\Exceptions\InternalError;
use Axessors\Exceptions\OopError;
use Axessors\Exceptions\TypeError;

class CallProcessor
{
    private $method;
    private $object;
    private $backtrace;
    private $mode;
    private $class;
    private $propertyData;
    private $reflection;

    public function __construct(array $backtrace, $object = null)
    {
        $this->object = $object;
        $this->class = $backtrace[1]['class'] ?? $backtrace[0]['class'];
        $this->backtrace = $backtrace[0];
    }

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
                throw new AxessorsError("conditions for {$this->backtrace['class']}::{$this->backtrace['method']}() did not pass");
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
                        /*try
                        {
                            if($this->checkType($subType, $subVar))
                            {
                                return true;
                            }
                        }
                        catch (TypeError $error) {}*/
                        $this->checkType($subType, $subVar);
                    }
                    return;
                }
            }
        }
        throw new TypeError("not a valid type of {$this->backtrace['class']}::\${$this->propertyData->getName()}");
    }

    private function executeHandlers($value)
    {
        $handlers = $this->mode ? $this->propertyData->getOutputHandlers() : $this->propertyData->getInputHandlers();
        foreach ($handlers as $handler) {
            if (strpos($handler, '`') !== false) {
                //$handler = trim($handler, '`');
                $handler = str_replace('\\`', '`', substr($handler, 1, strlen($handler) - 2));
                if (is_null($this->object)) {
                    $value = call_user_func([$this->reflection->name, '__axessors_execute_staic'], $handler, $value,
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

    private function executeCondition($condition, $value): bool
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

    private function in(\ReflectionClass $reflection): bool
    {
        return $reflection->getStartLine() <= $this->backtrace['line'] && $reflection->getEndLine() >= $this->backtrace['line'];
    }
}

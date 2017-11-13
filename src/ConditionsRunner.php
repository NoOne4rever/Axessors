<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors;

use NoOne4rever\Axessors\Exceptions\TypeError;

/**
 * Class ConditionsSuit.
 * 
 * Processes Axessors conditions.
 * 
 * @package NoOne4rever\Axessors
 */
class ConditionsRunner extends RunningSuit
{
    /** @var string method name */
    private $method;

    /**
     * ConditionsSuit constructor.
     * 
     * @param int $mode mode of execution
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
     * Checks the conditions defined in the Axessors comment.
     *
     * Creates logical tree of the conditions and then checks if the general result is true.
     *
     * @param $value mixed value of the property
     * @return bool result of checking of the conditions
     */
    public function processConditions($value): bool
    {
        $conditions = $this->calculateConditions($value);
        if (empty($conditions)) {
            return true;
        } else {
            return array_reduce($conditions, function ($carry, $item) {
                return $this->reduce($carry, $item, true);
            });
        }
    }

    /**
     * Reduces conditions tree and returns the result.
     * 
     * @param mixed $carry previous item
     * @param mixed $item item
     * @param bool $mode mode
     * @return bool the result of reducing
     */
    private function reduce($carry, $item, bool $mode): bool 
    {
        if ($item === $mode || $carry === $mode) {
            return $mode;
        } elseif (is_array($item)) {
            return array_reduce($item, function ($carry, $item) {
                return $this->reduce($carry, $item, false);
            });
        } else {
            return !$mode;
        }
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
        $conditions = $this->mode == RunningSuit::OUTPUT_MODE ? $this->propertyData->getOutputConditions() : $this->propertyData->getInputConditions();
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
            return $this->executeInjectedString($condition, $value, true);
        } else {
            return $this->runStandardCondition($condition, $value);
        }
    }

    /**
     * Runs Axessors condition.
     * 
     * @param string $condition condition
     * @param mixed $value value to check 
     * @return bool the result of the checkout
     */
    private function runStandardCondition(string $condition, $value): bool 
    {
        $value = $this->count($value);
        if (strpos($condition, '..') !== false) {
            $condition = explode('..', $condition);
            $condition = "<= {$condition[1]} && $value >= {$condition[0]}";
        }
        return eval("return $value $condition;");
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
                throw new TypeError('value "' . var_export($value, true) . "\" passed to {$this->class}::{$this->method}() is not countable");
        }
        return $value;
    }
}
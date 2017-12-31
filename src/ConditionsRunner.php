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
     * Casts the property to integer.
     *
     * If the property is string or array returns it's length.
     * If the property is integer of float returns the property itself.
     *
     * @param $value mixed value of the property
     * @return int integer value of the property
     * @throws TypeError if the property can't be turned into integer
     */
    public static function count($value): int
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
                throw new TypeError('value of ' . var_export($value, true) . ' is not countable');
        }
        return $value;
    }

    /**
     * Checks the conditions defined in the Axessors comment.
     *
     * Creates logical tree of the conditions and then checks if the general result is true.
     *
     * @param $var mixed value of the property
     * @return bool result of checking of the conditions
     */
    public function processConditions($var): bool
    {
        if ($this->mode == RunningSuit::INPUT_MODE) {
            $conditions = $this->propertyData->getInputConditions();
        } else {
            $conditions = $this->propertyData->getOutputConditions();
        }
        return $this->executeInjectedString($conditions, $var, $this->mode);
    }

    /**
     * Executes conditions.
     * 
     * @param string $expr conditions expression
     * @param mixed $var value to process
     * @param bool $mode mode of execution defined in RunningSuit
     * @return bool the result of condition
     */
    protected function executeInjectedString(string $expr, $var, bool $mode): bool 
    {
        if (is_null($this->object)) {
            return call_user_func("{$this->class}::__axessorsExecute", $expr, $var, $mode);
        } else {
            return $this->object->__axessorsExecute($expr, $var, $mode);
        }
    }
}
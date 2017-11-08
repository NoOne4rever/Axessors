<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors\Types;

/**
 * Class Numeric.
 * 
 * Describes float and integer types.
 * 
 * @package NoOne4rever\Axessors\Types
 */
abstract class Numeric implements TypeDefinition
{
    /**
     * Axessors method.
     *
     * Increments variable.
     *
     * @param $var mixed variable
     * @return int|float changed integer
     */
    public static function m_in_incrementPROPERTY($var)
    {
        return $var + 1;
    }

    /**
     * Axessors method.
     *
     * Decrements variable.
     *
     * @param $var mixed variable
     * @return int|float changed integer
     */
    public static function m_in_decrementPROPERTY($var)
    {
        return $var - 1;
    }

    /**
     * Axessors handler.
     *
     * Increments variable.
     *
     * @param $var mixed variable
     * @return int|float changed integer
     */
    public static function h_inc($var)
    {
        return $var + 1;
    }

    /**
     * Axessors handler.
     *
     * Decrements variable.
     *
     * @param $var mixed variable
     * @return int|float changed integer
     */
    public static function h_dec($var)
    {
        return $var - 1;
    }
}
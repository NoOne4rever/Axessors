<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors\Types;

/**
 * Class Iterateable.
 * 
 * General iterateable type.
 * 
 * @package NoOne4rever\Axessors
 */
abstract class Iterateable implements TypeDefinition
{
    /**
     * Checks if the variable if iterateable.
     * 
     * @param $var mixed variable
     * @return bool result of the checkout
     */
	final public static function is($var): bool
	{
		return is_iterable($var) && static::isThis($var);
	}

    /**
     * Checks the type of the variable.
     * 
     * @param $var mixed variable
     * @return bool result of the checkout
     */
	abstract protected static function isThis($var): bool;
}

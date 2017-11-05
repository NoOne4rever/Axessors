<?php
/**
 * Axessors method.
 *
 * Increments variable.
 *
 * @param $var mixed variable
 * @return int changed integer
 */

namespace NoOne4rever\Axessors\Types;

/**
 * Interface TypeDefinition.
 * 
 * Interface of Axessors type.
 */
interface TypeDefinition
{
    /**
     * Checks the type of the variable.
     * 
     * @param $var mixed variable
     * @return bool result of the checkout
     */
	public static function is($var): bool;
}

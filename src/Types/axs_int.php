<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @package NoOne4rever\Axessors
 * @license GPL
 */

namespace NoOne4rever\Axessors\Types;

/**
 * Class axs_int.
 * 
 * Replaces internal integer type.
 */
class axs_int implements TypeDefinition
{
    /**
     * Checks the type of the variable.
     * 
     * @param $var mixed variable
     * @return bool result of the checkout
     */
	public static function is($var): bool
	{
		return is_int($var) || (is_float($var) && (int) $var == $var);
	}

    /**
     * Axessors method.
     * 
     * Increments variable.
     * 
     * @param $var mixed variable
     * @return int changed integer
     */
	public static function m_in_incrementPROPERTY($var): int
	{
		return ++$var;
	}

    /**
     * Axessors handler.
     *
     * Increments variable.
     *
     * @param $var mixed variable
     * @return int changed integer
     */
	public static function h_inc($var): int
	{
		return ++$var;
	}
}

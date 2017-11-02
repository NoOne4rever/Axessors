<?php
/**
 * Axessors method.
 *
 * Increments variable.
 *
 * @param $var mixed variable
 * @return int changed integer
 */

namespace Axessors\Types;

/**
 * Class axs_string.
 * 
 * Replaces internal string type.
 */
class axs_string implements TypeDefinition
{
    /**
     * Checks the type of the variable.
     * 
     * @param $var mixed variable
     * @return bool result of the checkout
     */
	public static function is($var): bool
	{
		return is_string($var);
	}

    /**
     * Axessors handler.
     * 
     * Casts a string to uppercase.
     * 
     * @param $var mixed variable
     * @return string changed string
     */
	public static function h_upper($var): string
	{
		return strtoupper($var);
	}

    /**
     * Axessors handler.
     * 
     * Reverses a string.
     * 
     * @param $var mixed variable
     * @return string changed string
     */
	public static function h_reverse($var): string
	{
		return strrev($var);
	}
}

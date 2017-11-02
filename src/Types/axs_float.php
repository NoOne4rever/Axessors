<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @package Axessors
 * @license GPL
 */

namespace Axessors\Types;

/**
 * Class axs_float
 * 
 * Replaces internal float type.
 */
class axs_float implements TypeDefinition
{
    /**
     * Checks the type of the variable.
     * 
     * @param $var mixed variable
     * @return bool result of the checkout
     */
	public static function is($var): bool
	{
		return is_int($var) || is_float($var);
	}
}

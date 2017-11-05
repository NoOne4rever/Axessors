<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @package Axessors
 * @license GPL
 */

namespace NoOne4rever\Axessors\Types;

/**
 * Class axs_callable.
 * 
 * Replaces internal callable type.
 */
class axs_callable implements TypeDefinition
{
    /**
     * Checks the type of the variable.
     * 
     * @param $var mixed variable
     * @return bool result of the checkout
     */
	public static function is($var): bool
	{
		return is_callable($var);
	}
}

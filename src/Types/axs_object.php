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
 * Class axs_object.
 *
 * Replaces internal object type.
 */
class axs_object implements TypeDefinition
{
    /**
     * Checks the type of the variable.
     * 
     * @param $var mixed variable
     * @return bool result of the checkout
     */
	public static function is($var): bool
	{
		return is_object($var);
	}
}

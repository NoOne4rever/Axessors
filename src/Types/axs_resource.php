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
 * Class axs_resource.
 * 
 * Replaces internal resource type.
 */
class axs_resource implements TypeDefinition
{
    /**
     * Checks the type of the variable.
     * 
     * @param $var mixed variable
     * @return bool result of the checkout
     */
	public static function is($var): bool
	{
		return is_resource($var);
	}
}

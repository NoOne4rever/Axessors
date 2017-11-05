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
 * Class axs_mixed
 * 
 * Axessors mixed type.
 */
class axs_mixed implements TypeDefinition
{
    /**
     * Returns true.
     * 
     * @param $var mixed variable
     * @return bool always true
     */
	public static function is($var): bool
	{
		return true;
	}
}

<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors\Types;

/**
 * Class axs_bool.
 *
 * Replaces internal boolean type.
 *
 * @package NoOne4rever\Axessors
 */
class axs_bool implements TypeDefinition
{
    /**
     * Checks the type of the variable.
     *
     * @param $var mixed variable
     * @return bool result of the checkout
     */
    public static function is($var): bool
    {
        return is_bool($var);
    }
}

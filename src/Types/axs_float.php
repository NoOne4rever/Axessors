<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors\Types;

/**
 * Class axs_float.
 *
 * Replaces internal float type.
 *
 * @package NoOne4rever\Axessors
 */
class axs_float extends Numeric
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

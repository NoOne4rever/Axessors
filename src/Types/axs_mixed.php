<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors\Types;

/**
 * Class axs_mixed
 *
 * Axessors mixed type.
 *
 * @package NoOne4rever\Axessors
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

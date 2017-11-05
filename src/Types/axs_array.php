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
 * Class axs_array.
 * 
 * Replaces internal array type.
 */
class axs_array extends Iterateable
{
    /**
     * Checks the type of variable.
     * 
     * @param $var mixed variable
     * @return bool result of the checkout
     */
	protected static function isThis($var): bool
	{
		return is_array($var);
	}

    /**
     * Handler.
     * 
     * Flips an array.
     * 
     * @param $var mixed variable
     * @return array changed array
     */
	public static function h_flip($var): array
	{
		return array_flip($var);
	}

    /**
     * Handler.
     *
     * Shuffles an array.
     *
     * @param $var mixed variable
     * @return array changed array
     */
	public static function h_shuffle($var): array
	{
		return shuffle($var);
	}
}

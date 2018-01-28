<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors\Types;

/**
 * Class axs_array_ext.
 *
 * Extended array.
 *
 * @package NoOne4rever\Axessors
 */
class axs_array_ext extends axs_array
{
    /**
     * Axessors method.
     *
     * Deletes array item by index.
     *
     * @param $var mixed variable
     * @param array $args arguments
     * @return array changed array
     */
    public static function m_in_deletePROPERTY($var, array $args): array
    {
        $itemToDelete = $args[0];
        foreach ($var as &$item) {
            if ($item === $itemToDelete) {
                unset($item);
            }
        }

        return $var;
    }

    /**
     * Axessors method.
     *
     * Adds an item to array by index.
     *
     * @param $var mixed variable
     * @param array $args arguments
     * @return array changed array
     */
    public static function m_in_addPROPERTY($var, array $args): array
    {
        $value = $args[0];
        $key = $args[1] ?? null;

        if (is_null($key)) {
            $var[] = $value;
        } else {
            $var[$key] = $value;
        }
        return $var;
    }

    /**
     * Axessors method.
     *
     * Returns size of array.
     *
     * @param $var mixed variable
     * @return int array length
     */
    public static function m_out_getPROPERTYCount($var): int
    {
        return count($var);
    }
}

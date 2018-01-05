<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors;

use NoOne4rever\Axessors\Exceptions\InternalError;

/**
 * Class Data.
 *
 * Singleton.
 * Stores information about classes with one of the Axessors' traits.
 *
 * @package NoOne4rever\Axessors
 */
class Data
{
    /** @var ClassData[] classes with class' data */
    private static $classes = [];

    /**
     * Adds a class to the global data.
     *
     * @param ClassData $class class to add
     */
    public static function addClass(ClassData $class): void
    {
        self::$classes[$class->reflection->name] = $class;
    }

    /**
     * Returns class' data by the name given.
     *
     * @param string $name class' name
     * @return ClassData class' data
     * @throws InternalError if the class not found
     */
    public static function getClass(string $name): ClassData
    {
        if (isset(self::$classes[$name])) {
            return self::$classes[$name];
        } else {
            throw new InternalError("class \"$name\" not found in Axessors\\Data");
        }
    }
}

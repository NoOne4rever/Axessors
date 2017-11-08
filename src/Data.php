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
    /** @var Data an instance of this class */
    private static $instance;

    /** @var ClassData[] classes with class' data */
    private $classes = [];

    /**
     * Data constructor.
     *
     * Implements Singleton pattern.
     */
    private function __construct()
    {
    }

    /**
     * Adds a class to the global data.
     *
     * @param string $name class' name
     * @param ClassData $class class to add
     */
    public function addClass(string $name, ClassData $class): void
    {
        $this->classes[$name] = $class;
    }

    /**
     * Returns class' data by the name given.
     *
     * @param string $name class' name
     * @return ClassData class' data
     * @throws InternalError if the class not found
     */
    public function getClass(string $name): ClassData
    {
        if (isset($this->classes[$name])) {
            return $this->classes[$name];
        } else {
            throw new InternalError("class \"$name\" not found in Axessors\\Data");
        }
    }

    /**
     * Implements Singleton pattern.
     *
     * @return Data global data
     */
    public static function getInstance(): self
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}

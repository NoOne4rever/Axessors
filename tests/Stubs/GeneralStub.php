<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors\Tests\Stubs;

use NoOne4rever\Axessors\Axessors;

/**
 * Class GeneralStub.
 *
 * @package NoOne4rever\Axessors\Tests\Stubs
 *
 * @method string getInstanceField() getter for GeneralStub::$instanceField
 * @method static string getStaticField() getter for GeneralStub::$staticField
 */
class GeneralStub
{
    use Axessors;

    /** @var string static field with Axessors */
    public static $staticField = 'static'; #: +rdb
    /** @var string instance field with Axessors */
    public $instanceField = 'instance'; #: +rdb
    public $field = 'field value'; #: +rdb

    /**
     * Tests static method call.
     *
     * @return string result
     */
    public static function testStatic(): string
    {
        return 'static method';
    }

    /**
     * Tests instance method call.
     *
     * @return string result
     */
    public function testInstance(): string
    {
        return 'instance method';
    }

    /**
     * Tests general method.
     *
     * @return string result
     */
    public function getField(): string
    {
        return 'method result';
    }
}
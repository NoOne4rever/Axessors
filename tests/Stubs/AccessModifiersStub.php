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
 * Class AccessModifiersStub.
 *
 * @package NoOne4rever\Axessors\Tests\Stubs
 *
 * @method void setPublicAccess(int $val) setter for AccessModifiersStub::$publicAccess
 * @method void setProtectedAccess(int $val) setter for AccessModifiersStub::$protectedAccess
 * @method void setPrivateAccess(int $val) setter for AccessModifiersStub::$privateAccess
 */
class AccessModifiersStub
{
    use Axessors;

    /** @var int field with public setter */
    public $publicAccess; #: +wrt int
    /** @var int field with protected setter */
    public $protectedAccess; #: ~wrt int
    /** @var int field with private setter */
    public $privateAccess; #: -wrt int

    /**
     * Tests private setter.
     *
     * @param int $val new value
     */
    public function testPrivateAccess(int $val): void
    {
        $this->setPrivateAccess($val);
    }

    /**
     * Tests protected setter.
     *
     * @param int $val new value
     */
    public function testProtectedAccess(int $val): void
    {
        $this->setProtectedAccess($val);
    }

    /**
     * Tests public setter.
     *
     * @param int $val new value
     */
    public function testPublicAccess(int $val): void
    {
        $this->setPublicAccess($val);
    }
}
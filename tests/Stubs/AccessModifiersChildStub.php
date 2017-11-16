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
 * Class AccessModifiersChildStub.
 *
 * @package NoOne4rever\Axessors\Tests\Stubs
 */
class AccessModifiersChildStub extends AccessModifiersStub
{
    use Axessors;
    
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
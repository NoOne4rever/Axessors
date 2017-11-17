<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @package NoOne4rever\Axessors
 * @license GPL
 */

namespace NoOne4rever\Axessors\Tests\Stubs;

use NoOne4rever\Axessors\Axessors;

/**
 * Class AxessorsChildStub.
 *
 * @package NoOne4rever\Axessors\Tests\Stubs
 */
class AxessorsChildStub extends AxessorsStub
{
    use Axessors;

    /**
     * Tests protected setter.
     *
     * @param int $val new value
     */
    public function testProtected(int $val): void
    {
        $this->setProtected($val);
    }

    /**
     * Tests private setter.
     *
     * @param int $val new value
     */
    public function testPrivate(int $val): void
    {
        $this->setPrivate($val);
    }
}
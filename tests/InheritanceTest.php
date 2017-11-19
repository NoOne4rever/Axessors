<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @package NoOne4rever\Axessors
 * @license GPL
 */

namespace NoOne4rever\Axessors\Tests;

use NoOne4rever\Axessors\Tests\Stubs\AxessorsChildStub;
use NoOne4rever\Axessors\Tests\Stubs\AxessorsStub;

require __DIR__ . '/Stubs/AxessorsStub.php';
require __DIR__ . '/Stubs/AxessorsChildStub.php';

/**
 * Class AxessorsTest.
 *
 * Tests method generation.
 *
 * @package NoOne4rever\Axessors\Tests
 */
class InheritanceTest extends AxessorsTestCase
{
    /** @var AxessorsChildStub child stub */
    private $childStub;

    /**
     * Creates new stub instances.
     */
    public function setUp(): void
    {
        $this->stub = new AxessorsStub();
        $this->childStub = new AxessorsChildStub();
    }

    /**
     * Tests if public methods are inherited.
     */
    public function testPublicMethodsAreInherited(): void
    {
        $newValue = 5;

        $this->childStub->setPublic($newValue);

        $this->assertEquals($newValue, $this->childStub->public);
    }

    /**
     * Tests if protected methods are inherited.
     */
    public function testProtectedMethodsAreInherited(): void
    {
        $newValue = 5;

        $this->childStub->testProtected($newValue);

        $this->assertEquals($newValue, $this->childStub->protected);
    }

    /**
     * Tests if private methods are not inherited.
     *
     * Notice: Axessors will throw rather OopError than AxessorsError on private method call.
     *
     * @expectedException \NoOne4rever\Axessors\Exceptions\OopError
     */
    public function testPrivateMethodsAreNotInherited(): void
    {
        $newValue = 5;

        $this->childStub->testPrivate($newValue);
    }
}

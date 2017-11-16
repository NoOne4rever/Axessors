<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors\Tests;

use NoOne4rever\Axessors\Tests\Stubs\AccessModifiersChildStub;
use NoOne4rever\Axessors\Tests\Stubs\AccessModifiersStub;
use PHPUnit\Framework\TestCase;

require __DIR__ . '/Stubs/AccessModifiersStub.php';
require __DIR__ . '/Stubs/AccessModifiersChildStub.php';

/**
 * Class AccessModifiersTest.
 *
 * Tests Axessors methods access modifiers.
 *
 * @package NoOne4rever\Axessors\Tests
 */
class AccessModifiersTest extends TestCase
{
    /** @var AccessModifiersStub access modifiers stub */
    private $stub;
    /** @var AccessModifiersChildStub access modifiers child stub */
    private $childStub;

    /**
     * Creates new stub instances.
     */
    public function setUp(): void
    {
        $this->stub = new AccessModifiersStub();
        $this->childStub = new AccessModifiersChildStub();
    }

    /**
     * Tests if public Axessors method can be called from declaring class.
     */
    public function testPublicMethodCanBeCalledFromDeclaringClass(): void
    {
        $newValue = 5;

        $this->stub->testPublicAccess($newValue);

        $this->assertEquals($newValue, $this->stub->publicAccess);
    }

    /**
     * Tests if public Axessors method can be called from child class.
     */
    public function testPublicMethodCanBeCalledFromChildClass(): void
    {
        $newValue = 5;

        $this->childStub->testPublicAccess($newValue);

        $this->assertEquals($newValue, $this->childStub->publicAccess);
    }

    /**
     * Tests if public Axessors methods can be called outside declaring class.
     */
    public function testPublicMethodCanBeCalledFromOutside(): void
    {
        $newValue = 5;

        $this->stub->setPublicAccess($newValue);

        $this->assertEquals($newValue, $this->stub->publicAccess);
    }

    /**
     * Tests if protected Axessors method can be called from declaring class.
     */
    public function testProtectedMethodCanBeCalledFromDeclaringClass(): void
    {
        $newValue = 5;

        $this->stub->testProtectedAccess($newValue);

        $this->assertEquals($newValue, $this->stub->protectedAccess);
    }

    /**
     * Tests if protected Axessors method can be called from child class.
     */
    public function testProtectedMethodCanBeCalledFromChildClass(): void
    {
        $newValue = 5;

        $this->childStub->testProtectedAccess($newValue);

        $this->assertEquals($newValue, $this->childStub->protectedAccess);
    }

    /**
     * Tests if protected Axessors method can not be called from outside.
     *
     * @expectedException \NoOne4rever\Axessors\Exceptions\OopError
     */
    public function testProtectedMethodCanNotBeCalledFromOutside(): void
    {
        $newValue = 5;

        $this->stub->setProtectedAccess($newValue);
    }

    /**
     * Tests if private Axessors method can be called from declaring class.
     */
    public function testPrivateMethodCanBeCalledFromDeclaringClass(): void
    {
        $newValue = 5;

        $this->stub->testPrivateAccess($newValue);

        $this->assertEquals($newValue, $this->stub->privateAccess);
    }

    /**
     * Tests if protected Axessors method can not be called from child class.
     *
     * @expectedException \NoOne4rever\Axessors\Exceptions\OopError
     */
    public function testPrivateMethodCanNotBeCalledFromChildClass(): void
    {
        $newValue = 5;

        $this->childStub->testPrivateAccess($newValue);
    }

    /**
     * Tests if private Axessors method can not be called from outside.
     *
     * @expectedException \NoOne4rever\Axessors\Exceptions\OopError
     */
    public function testPrivateMethodCanNotBeCalledFromOutside(): void
    {
        $newValue = 5;

        $this->stub->setPrivateAccess($newValue);
    }
}

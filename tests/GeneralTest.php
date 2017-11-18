<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors\Tests;

use NoOne4rever\Axessors\Tests\Stubs\GeneralStub;
use PHPUnit\Framework\TestCase;

require __DIR__ . '/Stubs/GeneralStub.php';

/**
 * Class GeneralTest.
 *
 * @package NoOne4rever\Axessors\Tests
 */
class GeneralTest extends TestCase
{
    /** @var GeneralStub general Axessors stub */
    private $stub;

    /**
     * Creates new stub instance.
     */
    public function setUp(): void
    {
        $this->stub = new GeneralStub();
    }

    /**
     * Tests if an instance Axessors method can be called.
     */
    public function testInstanceAxessorsMethodCanBeCalled(): void
    {
        $this->assertEquals($this->stub->instanceField, $this->stub->getInstanceField());
    }

    /**
     * Tests if static Axessors method can be called.
     */
    public function testStaticAxessorsMethodCanBeCalled(): void
    {
        $this->assertEquals($this->stub::$staticField, $this->stub::getStaticField());
    }

    /**
     * Tests if an instance method can be called.
     */
    public function testInstanceMethodCanBeCalled(): void
    {
        $this->assertEquals('instance method', $this->stub->testInstance());
    }

    /**
     * Tests if static method can be called.
     */
    public function testStaticMethodCanBeCalled(): void
    {
        $this->assertEquals('static method', $this->stub::testStatic());
    }

    /**
     * Tests if a natural method has higher priority than an Axessors method.
     */
    public function testNaturalMethodHasHigherPriorityThanAxessorsMethod(): void
    {
        $this->assertNotEquals($this->stub->field, $this->stub->getField());
    }

    /**
     * Tests if non-existing method fails.
     *
     * @expectedException \NoOne4rever\Axessors\Exceptions\AxessorsError
     */
    public function testNonExistingMethodFails(): void
    {
        $this->stub->nonExisting();
    }
}

<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors\Tests;

use NoOne4rever\Axessors\Tests\Stubs\ConditionsStub;
use NoOne4rever\Axessors\Tests\Stubs\InjectedConditionsStub;
use PHPUnit\Framework\TestCase;

require __DIR__ . '/Stubs/InjectedConditionsStub.php';

/**
 * Class InjectedConditionsTest.
 *
 * Tests Axessors injected conditions.
 *
 * @package NoOne4rever\Axessors\Tests
 */
class InjectedConditionsTest extends TestCase
{
    /** @var InjectedConditionsStub */
    private $stub;

    /**
     * Creates new stub instance.
     */
    public function setUp()
    {
        $this->stub = new InjectedConditionsStub();
    }

    /**
     * Tests if true variable checkout can pass.
     */
    public function testTrueVarCheckoutCanPass(): void
    {
        $newValue = 101;

        $this->stub->setVarCheckout($newValue);

        $this->assertEquals($newValue, $this->stub->varCheckout);
    }

    /**
     * Tests if false variable checkout can not pass.
     *
     * @expectedException \NoOne4rever\Axessors\Exceptions\AxessorsError
     */
    public function testFalseVarCheckoutCanNotPass(): void
    {
        $newValue = 10;

        $this->stub->setVarCheckout($newValue);
    }

    /**
     * Tests if true internal field checkout can pass.
     */
    public function testTrueInternalFieldCheckoutCanPass(): void
    {
        $newValue = 10;
        $this->stub->condition = true;

        $this->stub->setInternalFieldCheckout($newValue);

        $this->assertEquals($newValue, $this->stub->internalFieldCheckout);
    }

    /**
     * Tests if false internal field checkout can not pass.
     *
     * @expectedException \NoOne4rever\Axessors\Exceptions\AxessorsError
     */
    public function testFalseInternalFieldCheckoutCanNotPass(): void
    {
        $newValue = 10;
        $this->stub->condition = false;

        $this->stub->setInternalFieldCheckout($newValue);
    }

    /**
     * Tests if true class checkout can pass.
     */
    public function testTrueClassCheckoutCanPass(): void
    {
        $newValue = new ConditionsStub();

        $this->stub->setClassCheckout($newValue);

        $this->assertInstanceOf(ConditionsStub::class, $this->stub->classCheckout);
    }

    /**
     * Tests if false class checkout can not pass.
     *
     * @expectedException \NoOne4rever\Axessors\Exceptions\AxessorsError
     */
    public function testFalseClassCheckoutCanNotPass(): void
    {
        $newValue = new \stdClass();

        $this->stub->setClassCheckout($newValue);
    }
}

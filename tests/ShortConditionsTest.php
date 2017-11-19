<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors\Tests;

use NoOne4rever\Axessors\Tests\Stubs\ShortConditionsStub;

require __DIR__ . '/Stubs/ShortConditionsStub.php';

/**
 * Class ShortConditionsTest.
 *
 * Tests Axessors mathematical expressions.
 *
 * @package NoOne4rever\Axessors\Tests
 */
class ShortConditionsTest extends AxessorsTestCase
{
    /**
     * Creates new stub instance.
     */
    public function setUp(): void
    {
        $this->stub = new ShortConditionsStub();
    }

    /**
     * Tests if true equality checkout can pass.
     */
    public function testTrueEqualityCheckoutCanPass(): void
    {
        $newValue = 5;

        $this->stub->setEquals($newValue);

        $this->assertEquals($newValue, $this->stub->equals);
    }

    /**
     * Tests if false equality checkout can not pass.
     *
     * @expectedException \NoOne4rever\Axessors\Exceptions\AxessorsError
     */
    public function testFalseEqualityCheckoutCanNotPass(): void
    {
        $newValue = 10;

        $this->stub->setEquals($newValue);
    }

    /**
     * Tests if true inequality checkout can pass.
     */
    public function testTrueInequalityCheckoutCanPass(): void
    {
        $newValue = 10;

        $this->stub->setNotEquals($newValue);

        $this->assertEquals($newValue, $this->stub->notEquals);
    }

    /**
     * Tests if false inequality checkout can not pass.
     *
     * @expectedException \NoOne4rever\Axessors\Exceptions\AxessorsError
     */
    public function testFalseInequalityCheckoutCanNotPass(): void
    {
        $newValue = 5;

        $this->stub->setNotEquals($newValue);
    }

    /**
     * Tests if true higher checkout can pass.
     */
    public function testTrueHigherCheckoutCanPass(): void
    {
        $newValue = 5;

        $this->stub->setHigher($newValue);

        $this->assertEquals($newValue, $this->stub->higher);
    }

    /**
     * Tests if false higher checkout can not pass.
     *
     * @expectedException \NoOne4rever\Axessors\Exceptions\AxessorsError
     */
    public function testFalseHigherCheckoutCanNotPass(): void
    {
        $newValue = 0;

        $this->stub->setHigher($newValue);
    }

    /**
     * Tests if true lower checkout can pass.
     */
    public function testTrueLowerCheckoutCanPass(): void
    {
        $newValue = 5;

        $this->stub->setLower($newValue);

        $this->assertEquals($newValue, $this->stub->lower);
    }

    /**
     * Tests if false lower checkout can not pass.
     *
     * @expectedException \NoOne4rever\Axessors\Exceptions\AxessorsError
     */
    public function testFalseLowerCheckoutCanNotPass(): void
    {
        $newValue = 10;

        $this->stub->setLower($newValue);
    }

    /**
     * Tests if true lower or equals checkout can pass.
     */
    public function testTrueLowerOrEqualsCheckoutCanPass(): void
    {
        $newValue = 10;

        $this->stub->setLowerOrEq($newValue);

        $this->assertEquals($newValue, $this->stub->lowerOrEq);
    }

    /**
     * Tests if false lower or equals checkout can not pass.
     *
     * @expectedException \NoOne4rever\Axessors\Exceptions\AxessorsError
     */
    public function testFalseLowerOrEqualsCheckoutCanNotPass(): void
    {
        $newValue = 11;

        $this->stub->setLowerOrEq($newValue);
    }

    /**
     * Tests if true higher or equals checkout can pass.
     */
    public function testTrueHigherOrEqualsCheckoutCanPass(): void
    {
        $newValue = 1;

        $this->stub->setHigherOrEq($newValue);

        $this->assertEquals($newValue, $this->stub->higherOrEq);
    }

    /**
     * Tests if false higher or equals checkout can not pass.
     *
     * @expectedException \NoOne4rever\Axessors\Exceptions\AxessorsError
     */
    public function testFalseHigherOrEqualsCheckoutCanNotPass(): void
    {
        $newValue = 0;

        $this->stub->setHigherOrEq($newValue);
    }

    /**
     * Tests if true range checkout can pass.
     */
    public function testTrueRangeCheckoutCanPass(): void
    {
        $newValue = 5;

        $this->stub->setRange($newValue);

        $this->assertEquals($newValue, $this->stub->range);
    }

    /**
     * Tests if false range checkout can not pass.
     *
     * @expectedException \NoOne4rever\Axessors\Exceptions\AxessorsError
     */
    public function testFalseRangeCheckoutCanNotPass(): void
    {
        $newValue = 15;

        $this->stub->setRange($newValue);
    }
}

<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors\Tests;

use NoOne4rever\Axessors\Tests\Stubs\ConditionsStub;

require __DIR__ . '/Stubs/ConditionsStub.php';

/**
 * Class ConditionsTest.
 *
 * Generally tests Axessors conditions.
 *
 * @package NoOne4rever\Axessors\Tests
 */
class ConditionsTest extends AxessorsTestCase
{
    /**
     * Creates new stub instance.
     */
    public function setUp(): void
    {
        $this->stub = new ConditionsStub();
    }

    /**
     * Tests if true input short conditions can pass.
     */
    public function testTrueOutputShortConditionsCanPass(): void
    {
        $this->assertEquals($this->stub->short, $this->stub->getShort());
    }

    /**
     * Tests if false input short conditions can not pass.
     *
     * @expectedException \NoOne4rever\Axessors\Exceptions\AxessorsError
     */
    public function testFalseOutputShortConditionsCanNotPass(): void
    {
        $this->stub->short = 'very long field value';

        $this->stub->getShort();
    }

    /**
     * Tests if true short conditions can pass.
     */
    public function testTrueInputShortConditionsCanPass(): void
    {
        $newValue = 'new short';

        $this->stub->setShort($newValue);

        $this->assertEquals($newValue, $this->stub->short);
    }

    /**
     * Tests if false short conditions can not pass.
     *
     * @expectedException \NoOne4rever\Axessors\Exceptions\AxessorsError
     */
    public function testFalseInputShortConditionsCanNotPass(): void
    {
        $newValue = 'very long field value';

        $this->stub->setShort($newValue);
    }

    /**
     * Tests if true output injected conditions can pass.
     */
    public function testTrueOutputInjectedConditionsCanPass(): void
    {
        $this->assertEquals($this->stub->injected, $this->stub->getInjected());
    }

    /**
     * Tests if false output injected conditions can not pass.
     *
     * @expectedException \NoOne4rever\Axessors\Exceptions\AxessorsError
     */
    public function testFalseOutputConditionsCanNotPass(): void
    {
        $this->stub->injected = 'something else';

        $this->stub->getInjected();
    }

    /**
     * Tests if true input injected conditions can pass.
     */
    public function testTrueInputInjectedConditionsCanPass(): void
    {
        $newValue = 'new injected';

        $this->stub->setInjected($newValue);

        $this->assertEquals($newValue, $this->stub->injected);
    }

    /**
     * Tests if false injected conditions can not pass.
     *
     * @expectedException \NoOne4rever\Axessors\Exceptions\AxessorsError
     */
    public function testFalseInputConditionsCanNotPass(): void
    {
        $newValue = 'something else';

        $this->stub->setInjected($newValue);
    }

    /**
     * Tests if not a valid output conditions fail.
     *
     * @expectedException \NoOne4rever\Axessors\Exceptions\ReThrownError
     */
    public function testInvalidOutputInjectedConditionsFail(): void
    {
        $this->stub->getInvalid();
    }
}

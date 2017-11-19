<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @package NoOne4rever\Axessors
 * @license GPL
 */

namespace NoOne4rever\Axessors\Tests;

use NoOne4rever\Axessors\Tests\Stubs\HandlersStub;

require __DIR__ . '/Stubs/HandlersStub.php';

/**
 * Class HandlersTest.
 *
 * Tests Axessors handlers.
 *
 * @package NoOne4rever\Axessors\Tests
 */
class HandlersTest extends AxessorsTestCase
{
    /**
     * Creates new stub instance.
     */
    public function setUp(): void
    {
        $this->stub = new HandlersStub();
    }

    /**
     * Tests if short input handlers are executed correctly.
     */
    public function testShortInputHandlersRunCorrectly(): void
    {
        $newValue = 'new short';

        $this->stub->setShort($newValue);

        $this->assertEquals(strtoupper($newValue), $this->stub->short);
    }

    /**
     * Tests if short output handlers are executed correctly.
     */
    public function testShortOutputHandlersRunCorrectly(): void
    {
        $this->assertEquals(strtolower($this->stub->short), $this->stub->getShort());
    }

    /**
     * Tests if injected input handlers are executed correctly.
     */
    public function testInjectedInputHandlersRunCorrectly(): void
    {
        $newValue = 'new injected';

        $this->stub->setInjected($newValue);

        $this->assertEquals(strtoupper($newValue), $this->stub->injected);
    }

    /**
     * Tests if injected output handlers are executed correctly.
     */
    public function testInjectedOutputHandlersRunCorrectly(): void
    {
        $this->assertEquals(strtolower($this->stub->injected), $this->stub->getInjected());
    }

    /**
     * Tests if invalid input handlers fail.
     *
     * @expectedException \NoOne4rever\Axessors\Exceptions\ReThrownError
     */
    public function testInvalidInputHandlersFail(): void
    {
        $newValue = 'new invalid';

        $this->stub->setInvalid($newValue);
    }

    /**
     * Tests if invalid output handlers fail.
     *
     * @expectedException \NoOne4rever\Axessors\Exceptions\ReThrownError
     */
    public function testInvalidOutputHandlersFail(): void
    {
        $this->stub->getInvalid();
    }

    /**
     * Tests if non-existing input handlers fail to run.
     *
     * @expectedException \NoOne4rever\Axessors\Exceptions\TypeError
     */
    public function testNonExistingInputHandlersFail(): void
    {
        $newValue = 'new value';

        $this->stub->setNonExisting($newValue);
    }

    /**
     * Tests if non-existing output handlers fail to run.
     *
     * @expectedException \NoOne4rever\Axessors\Exceptions\TypeError
     */
    public function testNonExistingOutputHandlersFail(): void
    {
        $this->stub->getNonExisting();
    }
}

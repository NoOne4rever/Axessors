<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors\Tests;

use NoOne4rever\Axessors\Tests\Stubs\CombinedHandlersStub;
use PHPUnit\Framework\TestCase;

require __DIR__ . '/Stubs/CombinedHandlersStub.php';

/**
 * Class CombinedHandlersTest.
 *
 * Tests multiple Axessors handlers.
 *
 * @package NoOne4rever\Axessors\Tests\Stubs
 */
class CombinedHandlersTest extends TestCase
{
    /** @var CombinedHandlersStub */
    private $stub;

    /**
     * Creates new stub instance.
     */
    public function setUp(): void
    {
        $this->stub = new CombinedHandlersStub();
    }

    /**
     * Tests if multiple short conditions are executed correctly.
     */
    public function testShortCombinedHandlersRunCorrectly(): void
    {
        $this->assertEquals($this->stub->short, $this->stub->getShort());
    }

    /**
     * Tests if multiple injected conditions are executed correctly.
     */
    public function testInjectedCombinedHandlersRunCorrectly(): void
    {
        $this->assertEquals($this->stub->injected, $this->stub->getInjected());
    }

    /**
     * Tests if multiple short and combined conditions are executed correctly.
     */
    public function testBothCombinedHandlersRunCorrectly(): void
    {
        $this->assertEquals($this->stub->both, $this->stub->getBoth());
    }
}

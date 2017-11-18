<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors\Tests;

use NoOne4rever\Axessors\Tests\Stubs\InjectedHandlersStub;
use NoOne4rever\Axessors\Tests\Stubs\NonAxessorsStub;
use PHPUnit\Framework\TestCase;

require __DIR__ . '/Stubs/InjectedHandlersStub.php';

/**
 * Class InjectedHandlersTest.
 *
 * Tests Axessors injected handlers.
 *
 * @package NoOne4rever\Axessors\Tests
 */
class InjectedHandlersTest extends TestCase
{
    /** @var InjectedHandlersStub stub with injected handlers */
    private $stub;

    /**
     * Creates new stub instance.
     */
    public function setUp(): void
    {
        $this->stub = new InjectedHandlersStub();
    }

    /**
     * Tests if input or output variable is processed by injected Axessors handlers.
     */
    public function testVarCanBeProcessedInInjectedHandlers(): void
    {
        $newValue = 'new value';

        $this->stub->setVarProcessing($newValue);

        $this->assertEquals(strtoupper($newValue), $this->stub->varProcessing);
    }

    /**
     * Tests if injected handlers can change internal class fields.
     */
    public function testAxessorsInjectedHandlersCanMakeSideEffects(): void
    {
        $newValue = 'new value';

        $this->stub->setInternalSideEffects($newValue);

        $this->assertEquals($newValue, $this->stub->field);
    }

    /**
     * Tests if class names in injected handlers are resolved correctly
     */
    public function testClassNamesInInjectedHandlersAreResolvedCorrectly(): void
    {
        $newValue = new \stdClass();

        $this->stub->setClassNameResolving($newValue);

        $this->assertInstanceOf(NonAxessorsStub::class, $this->stub->classNameResolving);
    }
}

<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors\Tests;

use NoOne4rever\Axessors\Tests\Stubs\InterfaceImplementationStub;
use PHPUnit\Framework\TestCase;

require __DIR__ . '/Stubs/AbstractStub.php';
require __DIR__ . '/Stubs/AbstractChildStub.php';
require __DIR__ . '/Stubs/StubInterface.php';
require __DIR__ . '/Stubs/InterfaceImplementationStub.php';

/**
 * Class InterfaceImplementationTest.
 *
 * Tests Axessors functionality connected with interface implementation.
 *
 * @package NoOne4rever\Axessors\Tests\Stubs
 */
class InterfaceImplementationTest extends TestCase
{
    /** @var InterfaceImplementationStub hierarchy scanning stub */
    private $stub;

    /**
     * Creates new stub instance.
     */
    public function setUp(): void
    {
        $this->stub = new InterfaceImplementationStub();
    }

    /**
     * Tests if Axessors getter was generated.
     */
    public function testGetterWasGenerated(): void
    {
        $this->assertEquals($this->stub->field, $this->stub->getField());
    }

    /**
     * Tests if Axessors setter was generated.
     */
    public function testSetterWasGenerated(): void
    {
        $newValue = 101;

        $this->stub->setField($newValue);

        $this->assertEquals($newValue, $this->stub->field);
    }
}

<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @package NoOne4rever\Axessors
 * @license GPL
 */

namespace NoOne4rever\Axessors\Tests;

use NoOne4rever\Axessors\Tests\Stubs\MultipleAxsDeclarationsStub;

require __DIR__ . '/Stubs/MultipleAxsDeclarationsStub.php';

/**
 * Class MultipleAxsDeclarationsTest.
 * 
 * Tests multiple Axessors declarations.
 * 
 * @package NoOne4rever\Axessors\Tests
 */
class MultipleAxsDeclarationsTest extends AxessorsTestCase
{
    /**
     * Creates new stub instance.
     */
    public function setUp(): void
    {
        $this->stub = new MultipleAxsDeclarationsStub();
    }

    /**
     * Tests if all Axessors getters can be called.
     */
    public function testAllGettersCanBeCalled(): void
    {
        $this->stub->red = 100;
        $this->stub->green = 101;
        $this->stub->blue = 102;
        
        $this->assertEquals($this->stub->red, $this->stub->getRed());
        $this->assertEquals($this->stub->green, $this->stub->getGreen());
        $this->assertEquals($this->stub->blue, $this->stub->getBlue());
    }

    /**
     * Tests if all Axessors setters can be called.
     */
    public function testAllSettersCanBeCalled(): void
    {
        $red = 100;
        $green = 101;
        $blue = 102;
        
        $this->stub->setRed($red);
        $this->stub->setGreen($green);
        $this->stub->setBlue($blue);
        
        $this->assertEquals($red, $this->stub->red);
        $this->assertEquals($green, $this->stub->green);
        $this->assertEquals($blue, $this->stub->blue);
    }
}

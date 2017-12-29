<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors\Tests;

use NoOne4rever\Axessors\Tests\Stubs\InjectedStringStub;

require __DIR__ . '/Stubs/InjectedStringStub.php';

/**
 * Class InjectedStringsTest.
 * 
 * Tests Axessors injected strings.
 * 
 * @package NoOne4rever\Axessors\Tests
 */
class InjectedStringsTest extends AxessorsTestCase
{
    /**
     * Creates new stub instance.
     */
    public function setUp(): void
    {
        $this->stub = new InjectedStringStub();
    }

    /**
     * Tests short "$." syntax.
     */
    public function testShortThisSyntax(): void
    {
        $this->stub->x = 1;
        $newValue = 256;
        
        $this->stub->setThisUsage($newValue);
        
        $this->assertEquals($newValue, $this->stub->thisUsage);
    }
}

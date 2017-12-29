<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors\Tests;

use NoOne4rever\Axessors\Tests\Stubs\DefaultPropertyStub;

require __DIR__ . '/Stubs/DefaultPropertyStub.php';

class DefaultPropertyTest extends AxessorsTestCase
{
    /**
     * Creates new stub instance.
     */
    public function setUp(): void
    {
        $this->stub = new DefaultPropertyStub();
    }

    /**
     * Tests default getter.
     */
    public function testDefaultGetter(): void
    {
        $this->stub->def = 256;
        
        $this->assertEquals($this->stub->def, $this->stub->get());
    }

    /**
     * Tests default setter.
     */
    public function testDefaultSetter(): void
    {
        $value = 256;
        
        $this->stub->set($value);
        
        $this->assertEquals($value, $this->stub->def);
    }
}

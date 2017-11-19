<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors\Tests;

use NoOne4rever\Axessors\Tests\Stubs\NonAxessorsStub;
use NoOne4rever\Axessors\Tests\Stubs\TypeDeclarationsStub;

require __DIR__ . '/Stubs/TypeDeclarationsStub.php';

/**
 * Class TypeDeclarationsTest.
 *
 * Tests Axessors type declarations.
 *
 * @package NoOne4rever\Axessors\Tests
 */
class TypeDeclarationsTest extends AxessorsTestCase
{
    /**
     * Creates new stub instance.
     */
    public function setUp(): void
    {
        $this->stub = new TypeDeclarationsStub();
    }

    /**
     * Tests valid data passed to setter can pass type checkout.
     */
    public function testValidSingleCanPass(): void
    {
        $newValue = 'something';

        $this->stub->setSingle($newValue);

        $this->assertEquals($newValue, $this->stub->single);
    }

    /**
     * Tests invalid data passed to setter can not pass type checkout.
     *
     * @expectedException \NoOne4rever\Axessors\Exceptions\TypeError
     */
    public function testInvalidSingleCanNotPass(): void
    {
        $newValue = 101;

        $this->stub->setSingle($newValue);
    }

    /**
     * Tests valid data passed to setter can pass type checkout.
     */
    public function testValidMultipleCanPass(): void
    {
        $newValue = 'no';
        $this->stub->setMultiple($newValue);
        $this->assertEquals($newValue, $this->stub->multiple);

        $newValue = false;
        $this->stub->setMultiple($newValue);
        $this->assertEquals($newValue, $this->stub->multiple);

        $newValue = 0;
        $this->stub->setMultiple($newValue);
        $this->assertEquals($newValue, $this->stub->multiple);
    }

    /**
     * Tests invalid data passed to setter can not pass type checkout.
     *
     * @expectedException \NoOne4rever\Axessors\Exceptions\TypeError
     */
    public function testInvalidMultipleCanNotPass(): void
    {
        $newValue = 256.512;
        $this->stub->setMultiple($newValue);
    }

    /**
     * Tests if valid data can pass typed array checkout.
     */
    public function testValidArrayCanPass(): void
    {
        $newValue = range('a', 'z');

        $this->stub->setArray($newValue);

        $this->assertEquals($newValue, $this->stub->array);
    }

    /**
     * Tests if invalid data can not pass typed array checkout.
     *
     * @expectedException \NoOne4rever\Axessors\Exceptions\TypeError
     */
    public function testInvalidArrayCanNotPass(): void
    {
        $newValue = range(1, 10);

        $this->stub->setArray($newValue);
    }

    /**
     * Tests if valid data can pass default type checkout.
     */
    public function testValidDefaultTypeCanPass(): void
    {
        $newValue = 101;

        $this->stub->setDefault($newValue);

        $this->assertEquals($newValue, $this->stub->default);
    }

    /**
     * Tests if invalid data can not pass default type checkout.
     *
     * @expectedException \NoOne4rever\Axessors\Exceptions\TypeError
     */
    public function testInvalidDefaultTypeCanNotPass(): void
    {
        $newValue = 'smth';

        $this->stub->setDefault($newValue);
    }

    /**
     * Tests valid data passed to setter can pass type checkout.
     */
    public function testValidClassCanPass(): void
    {
        $newValue = new NonAxessorsStub();

        $this->stub->setClass($newValue);

        $this->assertEquals($newValue, $this->stub->class);
    }

    /**
     * Tests invalid data passed to setter can not pass type checkout.
     *
     * @expectedException \NoOne4rever\Axessors\Exceptions\TypeError
     */
    public function testInvalidClassCanNotPass(): void
    {
        $newValue = new \stdClass();

        $this->stub->setClass($newValue);
    }

    /**
     * Tests valid data passed to setter can pass type checkout.
     */
    public function testValidExtendedCanPass(): void
    {
        $newValue = range(1, 100);

        $this->stub->setExtended($newValue);

        $this->assertEquals($newValue, $this->stub->extended);
    }

    /**
     * Tests invalid data passed to setter can not pass type checkout.
     *
     * @expectedException \NoOne4rever\Axessors\Exceptions\TypeError
     */
    public function testInvalidExtendedCanNotPass(): void
    {
        $newValue = 101;

        $this->stub->setExtended($newValue);
    }
}

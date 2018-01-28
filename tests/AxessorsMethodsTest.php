<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors\Tests;

use NoOne4rever\Axessors\Tests\Stubs\AxessorsMethodsStub;

require __DIR__ . '/Stubs/AxessorsMethodsStub.php';

/**
 * Class AxessorsMethodsTest.
 *
 * @package NoOne4rever\Axessors\Tests
 */
class AxessorsMethodsTest extends AxessorsTestCase
{
    /**
     * Creates new stub instance.
     */
    public function setUp(): void
    {
        $this->stub = new AxessorsMethodsStub();
        $this->stub->int = range(1, 10);
    }

    /**
     * Tests item deletion method.
     */
    public function testDeletionMethod(): void
    {
        $backup = $this->stub->int;
        $item = $this->stub->int[0];

        unset($backup[0]);
        $this->stub->deleteInt($item);

        $this->assertEquals($backup, $this->stub->int);
    }

    /**
     * Tests item addition method.
     */
    public function testAdditionMethod(): void
    {
        $backup = $this->stub->int;
        $index = 1;
        $newValue = 101;

        $backup[$index] = $newValue;
        $this->stub->addInt($newValue, $index);

        $this->assertEquals($backup, $this->stub->int);
    }

    /**
     * Tests count method.
     */
    public function testCountMethod(): void
    {
        $this->assertEquals(count($this->stub->int), $this->stub->getIntCount());
    }
}

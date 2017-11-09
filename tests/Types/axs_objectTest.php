<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors\Tests;

use NoOne4rever\Axessors\Types\axs_object;
use PHPUnit\Framework\TestCase;

/**
 * Tests Axessors object type.
 * 
 * @package NoOne4rever\Axessors
 */
class axs_objectTest extends TestCase
{
    /**
     * Tests if correct data can pass type checkout.
     * 
     * @dataProvider correctDataProvider
     * 
     * @param mixed $var variable
     */
    public function testCorrectDataCanPassTypeCheckout($var): void
    {
        $this->assertTrue(axs_object::is($var));
    }

    /**
     * Tests if incorrect data cannot pass type checkout.
     * 
     * @dataProvider incorrectDataProvider
     * 
     * @param mixed $var variable
     */
    public function testIncorrectDataCanNotPassTypeCheckout($var): void
    {
        $this->assertFalse(axs_object::is($var));
    }

    /**
     * Provides correct data.
     * 
     * @return array data
     */
    public function correctDataProvider(): array 
    {
        return [
            [new \stdClass()],
            [new class {}]
        ];
    }

    /**
     * Provides incorrect data.
     * 
     * @return array data
     */
    public function incorrectDataProvider(): array 
    {
        return [
            [256],
            [256.512],
            [true],
            ['smth'],
            [[]]
        ];
    }
}

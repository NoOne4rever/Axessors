<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors\Tests;

use NoOne4rever\Axessors\Types\axs_resource;
use PHPUnit\Framework\TestCase;

/**
 * Tests Axessors resource type.
 * 
 * @package NoOne4rever\Axessors
 */
class axs_resourceTest extends TestCase
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
        $this->assertTrue(axs_resource::is($var));
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
        $this->assertFalse(axs_resource::is($var));
    }

    /**
     * Provides correct data.
     * 
     * @return array data
     */
    public function correctDataProvider(): array 
    {
        return [
            [fopen('php://stdout', 'w')]
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
            [[]],
            [new \stdClass()]
        ];
    }
}

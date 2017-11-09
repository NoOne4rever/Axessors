<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors\Tests;

use NoOne4rever\Axessors\Types\axs_callable;
use PHPUnit\Framework\TestCase;

class axs_callableTest extends TestCase
{
    /**
     * Tests if callable type can pass type checkout.
     *
     * @dataProvider correctDataProvider
     *
     * @param mixed $var variable
     */
    public function testCorrectDataCanPassTypeCheck($var): void
    {
        $this->assertTrue(axs_callable::is($var));
    }

    /**
     * Tests if incorrect type cannot pass type checkout.
     *
     * @dataProvider incorrectDataProvider
     *
     * @param mixed $var variable
     */
    public function testCorrectDataCanNotPassTypeCheck($var): void
    {
        $this->assertFalse(axs_callable::is($var));
    }

    /**
     * Correct data provider.
     *
     * @return array data
     */
    public function correctDataProvider(): array
    {
        return [
            [
                function () {
                }
            ]
        ];
    }

    /**
     * Incorrect data provider.
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

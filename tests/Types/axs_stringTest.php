<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors\Tests;

use NoOne4rever\Axessors\Types\axs_string;
use PHPUnit\Framework\TestCase;

/**
 * Tests Axessors string type.
 *
 * @package NoOne4rever\Axessors
 */
class axs_stringTest extends TestCase
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
        $this->assertTrue(axs_string::is($var));
    }

    /**
     * Tests if incorrect data cannot pass type checkout.
     *
     * @dataProvider incorrectDataProvider
     *
     * @param $var
     */
    public function testIncorrectDataCanNotPassTypeCheckout($var): void
    {
        $this->assertFalse(axs_string::is($var));
    }

    /**
     * Tests if a string is cast to lowercase by Axessors handler.
     *
     * @dataProvider variablesToChangeProvider
     *
     * @param string $var variable
     */
    public function testStringIsCastToUppercaseByAxessorsHandler(string $var): void
    {
        $this->assertEquals(strtoupper($var), axs_string::h_upper($var));
    }

    /**
     * Tests if a string is inverted by Axessors handler.
     *
     * @dataProvider variablesToChangeProvider
     *
     * @param string $var variable
     */
    public function testStringIsInvertedByAxessorsHandler(string $var): void
    {
        $this->assertEquals(strrev($var), axs_string::h_reverse($var));
    }

    /**
     * Correct data provider.
     *
     * @return array data
     */
    public function correctDataProvider(): array
    {
        return [
            ['smth'],
            ['smth else'],
            ['something really else']
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
            [[]],
            [new \stdClass()]
        ];
    }

    /**
     * Provides variables to change.
     * 
     * @return array variables
     */
    public function variablesToChangeProvider(): array
    {
        return [
            ['a, b, c, d, e, f'],
            ['something special'],
            ['smth'],
            ['something else']
        ];
    }
}

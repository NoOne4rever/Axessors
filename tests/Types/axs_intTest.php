<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors\Tests;

use NoOne4rever\Axessors\Types\axs_int;
use PHPUnit\Framework\TestCase;

/**
 * Tests Axessors integer type.
 *
 * @package NoOne4rever\Axessors
 */
class axs_intTest extends TestCase
{
    /**
     * Tests if correct data pass.
     *
     * @dataProvider correctDataProvider
     *
     * @param $var mixed variable
     */
    public function testCorrectTypePasses($var): void
    {
        $this->assertTrue(axs_int::is($var));
    }

    /**
     * Tests if incorrect data does not pass.
     *
     * @dataProvider incorrectDataProvider
     *
     * @param $var mixed variable
     */
    public function testIncorrectTypeDoesNotPass($var): void
    {
        $this->assertFalse(axs_int::is($var));
    }

    /**
     * Provides correct data.
     *
     * @return array data
     */
    public function correctDataProvider(): array
    {
        return [
            [4],
            [3.0]
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
            [1.2],
            [true],
            ['smth'],
            [[]],
            [new \stdClass()]
        ];
    }
}

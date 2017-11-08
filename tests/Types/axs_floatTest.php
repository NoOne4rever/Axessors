<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors\Tests;

use NoOne4rever\Axessors\Types\axs_float;
use PHPUnit\Framework\TestCase;

/**
 * Tests Axessors float type.
 *
 * @package NoOne4rever\Axessors
 */
class axs_floatTest extends TestCase
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
        $this->assertTrue(axs_float::is($var));
    }

    /**
     * Tests if incorrect data does not pass.
     *
     * @dataProvider incorrectDataProvider
     *
     * @param $var mixed variable
     */
    public function testIncorrectTypesDoesNotPass($var): void
    {
        $this->assertFalse(axs_float::is($var));
    }

    /**
     * Provides correct data.
     *
     * @return array data
     */
    public function correctDataProvider(): array
    {
        return [
            [256],
            [256.512]
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
            [true],
            ['smth'],
            [[]],
            [new \stdClass()]
        ];
    }
}

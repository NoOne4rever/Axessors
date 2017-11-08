<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors\Tests;

use NoOne4rever\Axessors\Types\axs_array;
use PHPUnit\Framework\TestCase;

/**
 * Tests Axessors array type.
 * 
 * @package NoOne4rever\Axessors
 */
class axs_arrayTest extends TestCase
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
        $this->assertTrue(axs_array::is($var));
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
        $this->assertFalse(axs_array::is($var));
    }

    /**
     * Tests if an array is shuffled by handler.
     * 
     * @dataProvider variablesToChangeProvider
     * 
     * @param $var array variable
     */
    public function testArrayIsShuffledByHandler(array $var): void
    {
        while (true)
        {
            $modified = axs_array::h_shuffle($var);
            if ($modified !== $var) {
                $this->assertNotEquals($modified, $var);
                return;
            }
        }
    }

    /**
     * Tests if an array if flipped by handler.
     * 
     * @dataProvider variablesToChangeProvider
     * 
     * @param $var array variable
     */
    public function testArrayIsFlippedByHandler(array $var): void
    {
        $this->assertEquals(array_flip($var), axs_array::h_flip($var));
    }

    /**
     * Provides correct data.
     * 
     * @return array data
     */
    public function correctDataProvider(): array 
    {
        return [
            [[]],
            [[[]]]
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
            [1],
            [256.512],
            [true],
            ['smth'],
            [new \stdClass()]
        ];
    }

    /**
     * Provides arrays to change.
     *
     * @return array variables to change
     */
    public function variablesToChangeProvider(): array
    {
        return [
            [[1, 2, 3, 4]],
            [['smth', 'smth else', 'smth extra else']]
        ];
    }
}

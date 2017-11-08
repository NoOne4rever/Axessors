<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors\Tests;

use NoOne4rever\Axessors\Types\Numeric;
use PHPUnit\Framework\TestCase;

/**
 * Tests Numeric class.
 *
 * @package NoOne4rever\Axessors
 */
class NumericTest extends TestCase
{
    /**
     * Tests if a variable is incremented by handler.
     *
     * @dataProvider variablesToChangeProvider
     *
     * @param $var int|float variable
     */
    public function testVariableIsIncrementedByHandler($var): void
    {
        $this->assertEquals($var + 1, Numeric::h_inc($var));
    }

    /**
     * Tests if a variable is incremented by Axessors method.
     *
     * @dataProvider variablesToChangeProvider
     *
     * @param $var int|float variable
     */
    public function testVariableIsIncrementedByAxessorsMethod($var): void
    {
        $this->assertEquals($var + 1, Numeric::m_in_incrementPROPERTY($var));
    }

    /**
     * Tests if a variable is decremented by handler.
     *
     * @dataProvider variablesToChangeProvider
     *
     * @param $var int|float variable
     */
    public function testVariableIsDecrementedByHandler($var): void
    {
        $this->assertEquals($var - 1, Numeric::h_dec($var));
    }

    /**
     * Tests if a variable is decremented by Axessors method.
     *
     * @dataProvider variablesToChangeProvider
     *
     * @param $var int|float variable
     */
    public function testVariableIsDecrementedByAxessorsMethod($var): void
    {
        $this->assertEquals($var - 1, Numeric::m_in_decrementPROPERTY($var));
    }

    /**
     * Provides variables to change.
     *
     * @return array data
     */
    public function variablesToChangeProvider(): array
    {
        return [
            [256],
            [512],
            [256.512],
            [512.256]
        ];
    }
}

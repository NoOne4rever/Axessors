<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors\Tests;

use NoOne4rever\Axessors\Types\axs_mixed;
use PHPUnit\Framework\TestCase;

/**
 * Tests Axessors mixed type.
 *
 * @package NoOne4rever\Axessors
 */
class axs_mixedTest extends TestCase
{
    /**
     * Tests if all data can pass *mixed* type check.
     *
     * @dataProvider dataProvider
     *
     * @param mixed $var variable
     */
    public function testCorrectDataCanPassTypeCheck($var): void
    {
        $this->assertTrue(axs_mixed::is($var));
    }

    /**
     * Data provider for type check test.
     *
     * @return array data
     */
    public function dataProvider(): array
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

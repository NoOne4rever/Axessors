<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors\Tests;

use NoOne4rever\Axessors\Types\axs_array_ext;
use PHPUnit\Framework\TestCase;

/**
 * Tests extended Axessors array type.
 *
 * @package NoOne4rever\Axessors
 */
class axs_array_extTest extends TestCase
{
    /**
     * Tests if an array item is deleted by Axessors method.
     *
     * @dataProvider variablesToDeleteProvider
     *
     * @param array $var variable
     * @param array $args function arguments
     */
    public function testItemIsDeletedByAxessorsMethod(array $var, array $args): void
    {
        $modified = axs_array_ext::m_in_deletePROPERTY($var, $args);

        $this->assertFalse(in_array($args[0], $modified));
    }

    /**
     * Tests if an item is added to array by Axessors method.
     *
     * @dataProvider variablesToAddProvider
     *
     * @param array $var variable
     * @param array $args function arguments
     */
    public function testItemIsAddedByAxessorsMethod(array $var, array $args): void
    {
        $modified = axs_array_ext::m_in_addPROPERTY($var, $args);

        $this->assertEquals($modified[$args[1] ?? count($modified) - 1], $args[0]);
    }

    /**
     * Tests if array elements is counted correctly.
     *
     * @dataProvider variablesToCountProvider
     *
     * @param array $var variable
     */
    public function testArrayItemsIsCountedByAxessorsMethodCorrectly(array $var): void
    {
        $this->assertEquals(count($var), axs_array_ext::m_out_getPROPERTYCount($var));
    }

    /**
     * Provides arrays and variables to add.
     *
     * @return array data
     */
    public function variablesToAddProvider(): array
    {
        return [
            [[256, 512], [1024]],
            [[1, 2, 3, 4], [5, 'smth']],
            [['smth' => 1, 2], [3, 'smth']]
        ];
    }

    /**
     * Provides arrays and variables to delete.
     *
     * @return array data
     */
    public function variablesToDeleteProvider(): array
    {
        return [
            [[256, 512], [256]],
            [[1, 2, 3, 4], [3]],
            [['smth' => 1, 2], [1]]
        ];
    }

    /**
     * Provides arrays to count.
     *
     * @return array data
     */
    public function variablesToCountProvider(): array
    {
        return [
            [[1, 2, 3, 4]],
            [['a', 'smth', 'smth else', 1.2]],
            [['smth' => 0]]
        ];
    }
}

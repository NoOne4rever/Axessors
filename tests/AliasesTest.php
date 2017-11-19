<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors\Tests;

use NoOne4rever\Axessors\Tests\Stubs\AliasesStub;

require __DIR__ . '/Stubs/AliasesStub.php';

/**
 * Class AliasesTest.
 *
 * Tests Axessors field aliases.
 *
 * @package NoOne4rever\Axessors\Tests
 */
class AliasesTest extends AxessorsTestCase
{
    /**
     * Creates new stub instance.
     */
    public function setUp()
    {
        $this->stub = new AliasesStub();
    }

    /**
     * Tests if created alias method can be called.
     */
    public function testExistingAliasMethodCanBeCalled(): void
    {
        $newValue = 5;

        $this->stub->setExactField($newValue);

        $this->assertEquals($newValue, $this->stub->someField);
    }

    /**
     * Tests if non-existing alias method can not be called.
     *
     * @expectedException \NoOne4rever\Axessors\Exceptions\AxessorsError
     */
    public function testNonExistingAliasMethodCanNotBeCalled(): void
    {
        $newValue = 5;

        $this->stub->setSomeField($newValue);
    }
}

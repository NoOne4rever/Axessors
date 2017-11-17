<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors\Tests;

use NoOne4rever\Axessors\Tests\Stubs\CombinedConditionsStub;
use PHPUnit\Framework\TestCase;

require __DIR__ . '/Stubs/CombinedConditionsStub.php';

/**
 * Class CombinedConditionsTest.
 *
 * Tests logically combined Axessors conditions.
 *
 * @package NoOne4rever\Axessors\Tests
 */
class CombinedConditionsTest extends TestCase
{
    /** @var CombinedConditionsStub */
    private $stub;

    /**
     * Creates new stub instance.
     */
    public function setUp()
    {
        $this->stub = new CombinedConditionsStub();
    }

    /**
     * Tests if true conditions grouped by logical and can pass.
     */
    public function testTrueConditionsGroupedByLogicalAndCanPass(): void
    {
        $newValue = 7;

        $this->stub->setLogicalAnd($newValue);

        $this->assertEquals($newValue, $this->stub->logicalAnd);
    }

    /**
     * Tests if false conditions grouped by logical and can not pass.
     *
     * @expectedException \NoOne4rever\Axessors\Exceptions\AxessorsError
     */
    public function testFalseConditionsGroupedByLogicalAndCanNotPass(): void
    {
        $newValue = 5;

        $this->stub->setLogicalAnd($newValue);
    }

    /**
     * Tests if true conditions grouped by logical or can pass.
     */
    public function testTrueConditionsGroupedByLogicalOrCanPass(): void
    {
        $newValue = 7;

        $this->stub->setLogicalOr($newValue);

        $this->assertEquals($newValue, $this->stub->logicalOr);
    }

    /**
     * Tests if false conditions grouped by logical or can not pass.
     *
     * @expectedException \NoOne4rever\Axessors\Exceptions\AxessorsError
     */
    public function testFalseConditionsGroupedByLogicalOrCanNotPass(): void
    {
        $newValue = 100;

        $this->stub->setLogicalOr($newValue);
    }

    /**
     * Tests if different true conditions can pass.
     */
    public function testDifferentTrueConditionsCanPass(): void
    {
        $newValue = 7;

        $this->stub->setDifferent($newValue);

        $this->assertEquals($newValue, $this->stub->different);
    }

    /**
     * Tests if different false conditions can not pass.
     *
     * @expectedException \NoOne4rever\Axessors\Exceptions\AxessorsError
     */
    public function testFalseConditionsGroupedByDifferentCanNotPass(): void
    {
        $newValue = 5;

        $this->stub->setDifferent($newValue);
    }
}

<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors\Tests;

use NoOne4rever\Axessors\Tests\Stubs\KeywordsStub;
use PHPUnit\Framework\TestCase;

require __DIR__ . '/Stubs/KeywordsStub.php';

class KeywordsTest extends TestCase
{
    /** @var KeywordsStub */
    private $stub;

    public function setUp()
    {
        $this->stub = new KeywordsStub();
    }

    public function testCreatedGettersCanBeCalled(): void
    {
        $this->assertEquals($this->stub->shortRdb, $this->stub->getShortRdb());
        $this->assertEquals($this->stub->shortAxs, $this->stub->getShortAxs());
        $this->assertEquals($this->stub->fullRdb, $this->stub->getFullRdb());
        $this->assertEquals($this->stub->fullAxs, $this->stub->getFullAxs());
    }
}

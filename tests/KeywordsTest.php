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

/**
 * Class KeywordsTest.
 *
 * Tests Axessors keywords.
 *
 * @package NoOne4rever\Axessors\Tests
 */
class KeywordsTest extends TestCase
{
    /** @var KeywordsStub keywords stub */
    private $stub;

    /**
     * Creates new stub instance.
     */
    public function setUp()
    {
        $this->stub = new KeywordsStub();
    }

    /**
     * Tests if automatically generated getters can be called.
     */
    public function testCreatedGettersCanBeCalled(): void
    {
        $this->assertEquals($this->stub->shortRdb, $this->stub->getShortRdb());
        $this->assertEquals($this->stub->shortAxs, $this->stub->getShortAxs());
        $this->assertEquals($this->stub->fullRdb, $this->stub->getFullRdb());
        $this->assertEquals($this->stub->fullAxs, $this->stub->getFullAxs());
    }

    /**
     * Tests if automatically generated setters can be called.
     */
    public function testCreatedSettersCanBeCalled(): void
    {
        $newShortWrt = 'new short wrt';
        $newShortAxs = 'new short axs';
        $newFullWrt = 'new full wrt';
        $newFullAxs = 'new full axs';

        $this->stub->setShortWrt($newShortWrt);
        $this->stub->setShortAxs($newShortAxs);
        $this->stub->setFullWrt($newFullWrt);
        $this->stub->setFullAxs($newFullAxs);

        $this->assertEquals($newShortWrt, $this->stub->shortWrt);
        $this->assertEquals($newShortAxs, $this->stub->shortAxs);
        $this->assertEquals($newFullWrt, $this->stub->fullWrt);
        $this->assertEquals($newFullAxs, $this->stub->fullAxs);
    }
}

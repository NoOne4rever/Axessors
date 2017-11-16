<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Class StartupTest.
 *
 * Tests Axessors StartupPHP.
 *
 * @package NoOne4rever\Axessors\Tests
 */
class StartupTest extends TestCase
{
    /**
     * Tests Axessors startup file.
     */
    public function testStartup(): void
    {
        require __DIR__ . '/../src/Startup.php';

        $this->assertTrue(true);
    }
}

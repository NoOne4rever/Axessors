<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors\Tests;

use NoOne4rever\Axessors\AxessorsStartup;
use PHPUnit\Framework\TestCase;

/**
 * Class AxessorsTestCase.
 *
 * Common test sandbox.
 *
 * @package NoOne4rever\Axessors\Tests
 */
abstract class AxessorsTestCase extends TestCase
{
    /** @var object test stub */
    protected $stub;

    /**
     * Runs Axessors startup.
     */
    public static function setUpBeforeClass()
    {
        AxessorsStartup::run();
    }
}
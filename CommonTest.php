<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors\Tests;

use NoOne4rever\Axessors\Axessors;
use NoOne4rever\Axessors\AxessorsStartup;

require __DIR__ . '/vendor/autoload.php';

/**
 * Class TestCase.
 *
 * @package NoOne4rever\Axessors
 *
 * @method void setField(int $val) setter for TestCase::$field
 * @method int getField() getter for TestCase::$field
 */
class TestCase
{
    use Axessors;

    private $field; #: +axs int 1..10
}

AxessorsStartup::run();

$test = new TestCase();
$test->setField(1);
echo $test->getField();
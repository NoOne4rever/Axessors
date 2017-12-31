<?php

namespace NoOne4rever\Axessors\Tests;

use NoOne4rever\Axessors\Axessors;
use NoOne4rever\Axessors\AxessorsStartup;

require __DIR__ . '/Axessors.phar';

class TestCase
{
    use Axessors;
    
    private $field; #: +axs int (!= 4 && (1..10 || == 100) && `{return 'ok';}`)
}

AxessorsStartup::run();

$test = new TestCase();
$test->setField(100);
echo $test->getField();
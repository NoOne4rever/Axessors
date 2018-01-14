<?php

namespace NoOne4rever\Axessors\Tests;

use NoOne4rever\Axessors\{
    Axessors,
    AxessorsStartup
};

require __DIR__ . '/vendor/autoload.php';

class CommonTest
{
    use Axessors;

    private $field = 0; #: +axs int => default
}

AxessorsStartup::run();

$test = new CommonTest();
$test->increment();
echo $test->get();
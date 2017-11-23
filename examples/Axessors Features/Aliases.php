<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @package NoOne4rever\Axessors
 * @license GPL
 */

namespace NoOne4rever\Axessors\Examples;

use NoOne4rever\Axessors\{
    Axessors,
    AxessorsStartup,
    Exceptions\AxessorsError
};

require __DIR__ . '/../../vendor/autoload.php';

/**
 * Axessors aliases sample.
 *
 * @method string getExactField() getter for $someField
 */
class AliasesSample
{
    use Axessors;

    /** @var string a field */
    private $someField = 'value'; #: +rdb string => exactField
}

AxessorsStartup::run();

$test = new AliasesSample();

try {
    $test->getSomeField(); // Error.
} catch (AxessorsError $error) {
    echo $error->getMessage() . PHP_EOL;
    echo $test->getExactField() . PHP_EOL; // OK.
}
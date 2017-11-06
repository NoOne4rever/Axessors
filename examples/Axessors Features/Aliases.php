<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @package NoOne4rever\Axessors
 * @license GPL
 */

namespace NoOne4rever\Axessors\Examples;

use NoOne4rever\Axessors\Axessors;
use NoOne4rever\Axessors\Exceptions\AxessorsError;

require 'C:/Users/NoOne/Documents/GitHub/Axessors/vendor/autoload.php';

/**
 * Sample class.
 *
 * @method string getExactField() getter for $someField
 */
class SampleClass
{
    use Axessors;

    /** @var string a field */
    private $someField = 'value'; #> +rdb string => exactField
}

require 'C:/Users/NoOne/Documents/GitHub/Axessors/src/Startup.php';

$test = new SampleClass();

try {
    $test->getSomeField();
} catch (AxessorsError $error) {
    echo $error->getMessage() . PHP_EOL;
    echo $test->getExactField() . PHP_EOL;
}
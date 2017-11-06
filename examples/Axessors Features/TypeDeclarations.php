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
use NoOne4rever\Axessors\Exceptions\TypeError;

require 'C:/Users/NoOne/Documents/GitHub/Axessors/vendor/autoload.php';

class Type
{
}

/**
 * Sample class.
 *
 * @method void setInt(int $val) setter for $int
 * @method void setIntOrBoolOrString(mixed $val) setter for $intOrBoolOrString
 * @method void setCustomClass(Type $val) setter for $customClass
 * @method void setStdClass(\stdClass $val) setter for $stdClass
 * @method void setArrayOfArrayOfStrings(array [] $val) setter for $arrayOfArrayOfStrings
 */
class SampleClass
{
    use Axessors;

    private $int; #> +wrt int
    private $intOrBoolOrString; #> +wrt int|bool|string
    private $customClass; #> +wrt Type
    private $stdClass; #> +wrt \stdClass
    private $arrayOfArrayOfStrings; #> +wrt array[array[string]]
}

require 'C:/Users/NoOne/Documents/GitHub/Axessors/src/Startup.php';

$test = new SampleClass();

try {
    $test->setInt(function () {
    });
} catch (TypeError $error) {
    echo $error->getMessage() . PHP_EOL;
    $test->setInt(1);
}

try {
    $test->setIntOrBoolOrString(function () {
    });
} catch (TypeError $error) {
    echo $error->getMessage() . PHP_EOL;
    $test->setIntOrBoolOrString(1);
    $test->setIntOrBoolOrString(true);
    $test->setIntOrBoolOrString('value');
}

try {
    $test->setCustomClass(function () {
    });
} catch (TypeError $error) {
    echo $error->getMessage() . PHP_EOL;
    $test->setCustomClass(new Type());
}

try {
    $test->setStdClass(function () {
    });
} catch (TypeError $error) {
    echo $error->getMessage() . PHP_EOL;
    $test->setStdClass(new \stdClass());
}

try {
    $test->setArrayOfArrayOfStrings([['1', '2', 3]]);
} catch (TypeError $error) {
    echo $error->getMessage() . PHP_EOL;
    $test->setArrayOfArrayOfStrings([['1']]);
}
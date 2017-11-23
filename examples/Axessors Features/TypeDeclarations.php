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
    Exceptions\TypeError
};

require __DIR__ . '/../../vendor/autoload.php';

/** Simple class. */
class Type
{
}

/**
 * Axessors type declarations sample.
 *
 * @method void setInt(int $val) setter for $int
 * @method void setIntOrBoolOrString(mixed $val) setter for $intOrBoolOrString
 * @method void setCustomClass(Type $val) setter for $customClass
 * @method void setStdClass(\stdClass $val) setter for $stdClass
 * @method void setArrayOfArrayOfStrings(array [] $val) setter for $arrayOfArrayOfStrings
 */
class TypeDefSample
{
    use Axessors;

    /** @var int integer field */
    private $int; #: +wrt int
    /** @var mixed integer or boolean or string field */
    private $intOrBoolOrString; #: +wrt int|bool|string
    /** @var Type custom class field */
    private $customClass; #: +wrt Type
    /** @var \stdClass standard class */
    private $stdClass; #: +wrt \stdClass
    /** @var array multidimensional array */
    private $arrayOfArrayOfStrings; #: +wrt array[array[string]]
}

AxessorsStartup::run();

$test = new TypeDefSample();

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
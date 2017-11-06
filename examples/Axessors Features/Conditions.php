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
 * Class SampleClass.
 *
 * @method void setShortCondition(int $val) setter for $shortCondition
 * @method void setFullCondition(string $val) setter for $fullCondition
 * @method void setMultipleConditions(string[] $val) setter for $multipleConditions
 * @method void setCombinedConditions(mixed $val) setter for $combinedConditions
 */
class SampleClass
{
    use Axessors;

    /** @var int field with short condition */
    private $shortCondition; #> +wrt int 1..10
    /** @var string field with full condition */
    private $fullCondition;  #> +wrt string `!is_null($this->shortCondition)`
    /** @var string[] field with several conditions */
    private $multipleConditions; #> +wrt array[string] `$this->shortCondition == 9` && `$this->fullCondition == 'value'`
    /** @var mixed field with several conditions and logical operators */
    private $combinedConditions; #> +wrt mixed `1 == 1` && `1 == 2` || `true`
}

require 'C:/Users/NoOne/Documents/GitHub/Axessors/src/Startup.php';

$sample = new SampleClass();

try {
    $sample->setFullCondition('value');
} catch (AxessorsError $error) {
    echo $error->getMessage() . PHP_EOL;
}

try {
    $sample->setShortCondition(-2);
} catch (AxessorsError $error) {
    echo $error->getMessage() . PHP_EOL;
    $sample->setShortCondition(2);
}

$sample->setFullCondition('value');

try {
    $sample->setMultipleConditions(['smth']);
} catch (AxessorsError $error) {
    echo $error->getMessage() . PHP_EOL;
    $sample->setShortCondition(9);
    $sample->setMultipleConditions(['smth2']);
}

$sample->setCombinedConditions(1.1);
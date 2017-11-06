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

require 'C:/Users/NoOne/Documents/GitHub/Axessors/vendor/autoload.php';

/**
 * Class TestCase.
 *
 * @method mixed getField() getter for $field
 * @method void setField(mixed $val) setter for $field
 * @method static mixed getStaticField() getter for $staticField
 * @method static void setStaticField(mixed $val) setter for $staticField
 */
class SampleClass
{
    use Axessors;

    /** @var mixed static field */
    private static $staticField; #> +axs mixed

    /** @var mixed instance field */
    private $field; #> +axs mixed
}

require 'C:/Users/NoOne/Documents/GitHub/Axessors/src/Startup.php';

SampleClass::setStaticField('static value');
echo SampleClass::getStaticField() . PHP_EOL;

$sample = new SampleClass();
$sample->setField('instance value');
echo $sample->getField() . PHP_EOL;
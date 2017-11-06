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
 * Sample class.
 *
 * @method string getShortHandlers() getter for $shortHandlers
 * @method string getInjectedHandlers() getter for $injectedHandlers
 * @method void setShortHandlers(string $val) setter for $shortHandlers
 * @method void setInjectedHandlers(string $val) setter for $injectedHandlers
 */
class SampleClass
{
    use Axessors;

    /** @var string field with short handlers */
    private $shortHandlers = 'value'; #> +rdb string >> upper, reverse
    /** @var string field with injected handlers */
    private $injectedHandlers = 'value'; #> +rdb string >> `$var = 'injectedConditions\' ' . $var`, `$var{0} = 'I'`
}

require 'C:/Users/NoOne/Documents/GitHub/Axessors/src/Startup.php';

$sample = new SampleClass();

echo $sample->getShortHandlers() . PHP_EOL;
echo $sample->getInjectedHandlers() . PHP_EOL;
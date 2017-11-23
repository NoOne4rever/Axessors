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
    Axessors, AxessorsStartup
};

require __DIR__ . '/../../vendor/autoload.php';

/**
 * Axessors handlers sample.
 *
 * @method string getShortHandlers() getter for $shortHandlers
 * @method string getInjectedHandlers() getter for $injectedHandlers
 * @method void setShortHandlers(string $val) setter for $shortHandlers
 * @method void setInjectedHandlers(string $val) setter for $injectedHandlers
 */
class HandlersSample
{
    use Axessors;

    /** @var string field with short handlers */
    private $shortHandlers = 'value'; #: +rdb string -> upper, reverse
    /** @var string field with injected handlers */
    private $injectedHandlers = 'value'; #: +rdb string -> `$var = 'injectedConditions\' ' . $var`, `$var{0} = 'I'`
}

AxessorsStartup::run();

$sample = new HandlersSample();

echo $sample->getShortHandlers() . PHP_EOL;
echo $sample->getInjectedHandlers() . PHP_EOL;
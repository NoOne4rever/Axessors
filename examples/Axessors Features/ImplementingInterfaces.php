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
    Axs,
    Axessors,
    AxessorsStartup
};

require __DIR__ . '/../../vendor/autoload.php';

/** Interface iSample. */
interface iSample
{
    # public function getField
}

/** Class IncrementSample. */
abstract class IncrementSample
{
    use Axs;

    # abstract protected function incrementField
    # abstract protected function setField
}

/**
 * Interface implementation sample.
 *
 * @method string|int getField() getter for InterfaceImplementationSample::$field
 * @method void setField() setter for InterfaceImplementationSample::$field
 * @method void incrementField() increments InterfaceImplementationSample::$field
 */
class InterfaceImplementationSample extends IncrementSample implements iSample
{
    use Axessors;

    /** @var string a field */
    private $field = 'value'; #: ~wrt string|int +rdb
}

AxessorsStartup::run();

$sample = new InterfaceImplementationSample();
echo $sample->getField() . PHP_EOL;
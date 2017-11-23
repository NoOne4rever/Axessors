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
    AxessorsStartup
};

require __DIR__ . '/../../vendor/autoload.php';

/**
 * General Axessors sample.
 *
 * @method mixed getField() getter for $field
 * @method void setField(mixed $val) setter for $field
 * @method static mixed getStaticField() getter for $staticField
 * @method static void setStaticField(mixed $val) setter for $staticField
 */
class GeneralSample
{
    use Axessors;

    /** @var mixed static field */
    private static $staticField; #: +axs mixed

    /** @var mixed instance field */
    private $field; #: +axs mixed
}

AxessorsStartup::run();

GeneralSample::setStaticField('static value');
echo GeneralSample::getStaticField() . PHP_EOL;

$sample = new GeneralSample();
$sample->setField('instance value');
echo $sample->getField() . PHP_EOL;
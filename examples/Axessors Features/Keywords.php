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
 * @method mixed getFullReadable() getter for $fullReadable
 * @method mixed setFullWritable(mixed $val) getter for $fullWritable
 * @method mixed getFullAccessible() getter for $fullAccessible
 * @method mixed setFullAccessible(mixed $val) getter for $fullAccessible
 * @method mixed getShortReadable() getter for $shortReadable
 * @method mixed setShortWritable(mixed $val) getter for $shortWritable
 * @method mixed getShortAccessible() getter for $shortAccessible
 * @method mixed setShortAccessible(mixed $val) getter for $shortAccessible
 */
class SampleClass
{
    use Axessors;
    
    private $fullReadable = 2; #> +readable mixed
    private $fullWritable = 4; #> +writable mixed
    private $fullAccessible = 8; #> +accessible mixed

    private $shortReadable = 16; #> +rdb mixed
    private $shortWritable = 32; #> +wrt mixed
    private $shortAccessible = 64; #> +axs mixed
}

require 'C:/Users/NoOne/Documents/GitHub/Axessors/src/Startup.php';

$sample = new SampleClass();

echo $sample->getFullReadable() . PHP_EOL;
$sample->setFullWritable(1);
echo $sample->getFullAccessible() . PHP_EOL;
$sample->setFullAccessible(1);

echo $sample->getShortReadable() . PHP_EOL;
$sample->setShortWritable(1);
echo $sample->getShortAccessible() . PHP_EOL;
$sample->setShortAccessible(1);
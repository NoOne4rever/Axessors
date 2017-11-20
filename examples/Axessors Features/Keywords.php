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
 * Axessors keywords sample.
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
class KeywordsSample
{
    use Axessors;

    /** @var int with full rdb keyword */
    private $fullReadable = 2; #> +readable
    /** @var int with full wrt keyword */
    private $fullWritable = 4; #> +writable
    /** @var int with full axs keyword */
    private $fullAccessible = 8; #> +accessible
    /** @var int with short rdb keyword */
    private $shortReadable = 16; #> +rdb
    /** @var int with short wrt keyword */
    private $shortWritable = 32; #> +wrt
    /** @var int with short axs keyword */
    private $shortAccessible = 64; #> +axs
}

AxessorsStartup::run();

$sample = new KeywordsSample();

echo $sample->getFullReadable() . PHP_EOL;
$sample->setFullWritable(1);
echo $sample->getFullAccessible() . PHP_EOL;
$sample->setFullAccessible(1);

echo $sample->getShortReadable() . PHP_EOL;
$sample->setShortWritable(1);
echo $sample->getShortAccessible() . PHP_EOL;
$sample->setShortAccessible(1);
<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @package NoOne4rever\Axessors
 * @license GPL
 */

namespace NoOne4rever\Axessors\Tests\Stubs;

use NoOne4rever\Axessors\Axessors;

/**
 * Stub of multiple Axessors declarations.
 * 
 * Here we have three fields with the same Axessors methods.
 * So it is sensible to write them in one line.
 * 
 * @package NoOne4rever\Axessors\Tests\Stubs
 * 
 * @method int getRed() getter for MultipleAxsDeclarationsStub::$red
 * @method int getGreen() getter for MultipleAxsDeclarationsStub::$green
 * @method int getBlue() getter for MultipleAxsDeclarationsStub::$blue
 * @method void setRed(int $val) setter for MultipleAxsDeclarationsStub::$red
 * @method void setGreen(int $val) setter for MultipleAxsDeclarationsStub::$green
 * @method void setBlue(int $val) setter for MultipleAxsDeclarationsStub::$blue
 */
class MultipleAxsDeclarationsStub
{
    use Axessors;
    
    /** @var int colors */
    public $red, $green, $blue; #: +axs int 0..255
}
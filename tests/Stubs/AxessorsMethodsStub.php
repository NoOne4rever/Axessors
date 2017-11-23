<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors\Tests\Stubs;

use NoOne4rever\Axessors\Axessors;

/**
 * Class AxessorsMethodsStub.
 *
 * @package NoOne4rever\Axessors\Tests\Stubs
 *
 * @method void deleteInt($index) deletes array element by index
 * @method void addInt(int $value, $index) add an element to array by index
 * @method int countInt() counts array elements
 */
class AxessorsMethodsStub
{
    use Axessors;

    public $int; #: +axs Array[int]
}
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
 * Class InvalidDefaultTypeStub.
 *
 * @package NoOne4rever\Axessors\Tests\Stubs
 */
class InvalidDefaultTypeStub
{
    use Axessors;

    public $field = false; #> +wrt int 
}
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
 * Class DefaultPropertyStub.
 * 
 * @package NoOne4rever\Axessors\Tests\Stubs
 */
class DefaultPropertyStub
{
    use Axessors;
    
    public $def; #: +axs int => default
}
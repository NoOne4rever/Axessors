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
 * Stub for injected strings.
 * 
 * @package NoOne4rever\Axessors\Tests\Stubs
 */
class InjectedStringStub
{
    use Axessors;
    
    public $x;
    public $thisUsage; #: +axs int `isset($.x)`
}
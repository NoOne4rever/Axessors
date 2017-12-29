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
    
    /** @var int conditional var */
    public $x;
    /** @var int with short "this" syntax */
    public $thisUsage; #: +axs int `isset($.x)`
    /** @var int with code block in callback */
    public $block = 1; #: +rdb int -> `{echo $var;}`
}
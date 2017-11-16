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
 * Class AliasesStub.
 *
 * @package NoOne4rever\Axessors\Tests\Stubs
 *
 * @method void setExactField(int $val) setter for AliasesStub::$someField
 */
class AliasesStub
{
    use Axessors;

    /** @var int field with Axessors alias */
    public $someField; #> +wrt int => exactField
}
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
 * Class ShortConditionsStub.
 *
 * @package NoOne4rever\Axessors\Tests\Stubs
 *
 * @method void setRange(int $val) setter for ShortConditionsStub::$range
 * @method void setHigher(int $val) setter for ShortConditionsStub::$higher
 * @method void setLower(int $val) setter for ShortConditionsStub::$lower
 * @method void setHigherOrEq(int $val) setter for ShortConditionsStub::$higherOrEq
 * @method void setLowerOrEq(int $val) setter for ShortConditionsStub::$lowerOrEq
 * @method void setEquals(int $val) setter for ShortConditionsStub::$equals
 * @method void setNotEquals(int $val) setter for ShortConditionsStub::$notEquals
 */
class ShortConditionsStub
{
    use Axessors;

    public $range; #: +wrt int 1..10 
    public $higher; #: +wrt int > 1
    public $lower; #: +wrt int < 10
    public $higherOrEq; #: +wrt int >= 1
    public $lowerOrEq; #: +wrt int <= 10 
    public $equals; #: +wrt int == 5
    public $notEquals; #: +wrt int != 5
}
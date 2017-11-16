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
 * Class InjectedConditionsStub.
 *
 * @package NoOne4rever\Axessors\Tests\Stubs
 *
 * @method void setVarCheckout(int $val) setter for InjectedConditionsStub::$varCheckout
 * @method void setInternalFieldCheckout(int $val) setter for InjectedConditionsStub::$internalFieldCheckout
 * @method void setClassCheckout(object $val) setter for InjectedConditionsStub::$classCheckout
 */
class InjectedConditionsStub
{
    use Axessors;

    /** @var bool internal condition */
    public $condition;
    /** @var int with input checkout */
    public $varCheckout; #> +wrt int `$var == 101`
    /** @var int with internal condition checkout */
    public $internalFieldCheckout; #> +wrt int `$this->condition`
    /** @var object with instance checkout */
    public $classCheckout; #> +wrt object `$var instanceof :ConditionsStub`
}
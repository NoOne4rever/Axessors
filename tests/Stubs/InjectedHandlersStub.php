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
 * Class InjectedHandlersStub.
 *
 * @package NoOne4rever\Axessors\Tests\Stubs
 *
 * @method void setVarProcessing(string $val) setter for InjectedHandlersStub::$varProcessing
 * @method void setClassNameResolving(object $val) setter for InjectedHandlersStub::$classNameResolving
 * @method void setInternalSideEffects(string $val) setter for InjectedHandlersStub::$internalSideEffects
 */
class InjectedHandlersStub
{
    use Axessors;

    /** @var string internal class field */
    public $field;
    /** @var string with Axessors side effects */
    public $internalSideEffects; #> +wrt string >> `$this->field = 'new value'`
    /** @var string with var processing */
    public $varProcessing; #> +wrt string >> `$var = strtoupper($var)`
    /** @var object with class name resolving functionality */
    public $classNameResolving; #> +wrt object >> `$var = new :NonAxessorsStub()`
}
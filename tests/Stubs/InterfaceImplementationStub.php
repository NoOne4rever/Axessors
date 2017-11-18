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
 * Class InterfaceImplementationStub.
 *
 * @package NoOne4rever\Axessors\Tests\Stubs
 *
 * @method int getField() getter for InterfaceImplementationStub::$field
 * @method int setField(int $val) setter for InterfaceImplementationStub::$field
 */
class InterfaceImplementationStub extends AbstractChildStub implements StubInterface
{
    use Axessors;

    public $field; #> +axs int
}
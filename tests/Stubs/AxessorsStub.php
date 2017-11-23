<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @package NoOne4rever\Axessors
 * @license GPL
 */

namespace NoOne4rever\Axessors\Tests\Stubs;

use NoOne4rever\Axessors\Axessors;

/**
 * Class AxessorsStub.
 *
 * @package NoOne4rever\Axessors\Tests\Stubs
 *
 * @method void setPublic(int $val) setter for AxessorsStub::$public
 * @method void setProtected(int $val) setter for AxessorsStub::$protected
 * @method void setPrivate(int $val) setter for AxessorsStub::$private
 */
class AxessorsStub
{
    use Axessors;

    public $public; #: +wrt int
    public $protected; #: ~wrt int
    public $private; #: -wrt int
}
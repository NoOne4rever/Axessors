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
 * Class HandlersStub.
 *
 * @package NoOne4rever\Axessors\Tests\Stubs
 *
 * @method void setShort(string $val) setter for HandlersStub::$short
 * @method void setInjected(string $val) setter for HandlersStub::$injected
 * @method void setInvalid(string $val) setter for HandlersStub::$invalid
 * @method void setNonExisting(string $val) setter for HandlersStub::$nonExisting
 * @method string getShort() getter for HandlersStub::$short
 * @method string getInjected() getter for HandlersStub::$injected
 * @method string getInvalid() getter for HandlersStub::$invalid
 * @method string getNonExisting() getter for HandlersStub::$nonExisting
 */
class HandlersStub
{
    use Axessors;

    /** @var string with short handlers */
    public $short = 'short'; #: +wrt -> upper +rdb -> lower
    /** @var string with full handlers */
    public $injected = 'injected'; #: +wrt -> `$var = strtoupper($var)` +rdb -> `$var = strtolower($var)`
    /** @var string with invalid handler */
    public $invalid = 'invalid'; #: +wrt -> `not a valid handler` +rdb -> `not a valid handler`
    /** @var string with non-existing handlers */
    public $nonExisting = 'non existing'; #: +wrt -> inc +rdb -> inc 
}
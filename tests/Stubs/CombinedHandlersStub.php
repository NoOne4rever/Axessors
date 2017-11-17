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
 * Class CombinedHandlersStub.
 *
 * @package NoOne4rever\Axessors\Tests\Stubs
 *
 * @method string getShort() getter for CombinedHandlerStub::$short
 * @method string getInjected() getter for CombinedHandlerStub::$injected
 * @method string getBoth() getter for CombinedHandlerStub::$both
 */
class CombinedHandlersStub
{
    use Axessors;

    /** @var string with combined short handlers */
    public $short = 'short'; #> +rdb >> upper, lower
    /** @var string with combined injected handlers */
    public $injected = 'injected'; #> +rdb >> `$var = strtoupper($var)`, `$var = strtolower($var)`
    /** @var string with both handlers types */
    public $both = 'both'; #> +rdb >> upper, `$var = strtolower($var)`
}
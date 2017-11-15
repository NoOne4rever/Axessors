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
 * Class ConditionsStub.
 *
 * @package NoOne4rever\Axessors\Tests\Stubs
 *
 * @method string getShort() getter for ConditionsStub::$short
 * @method string setShort(string $val) setter for ConditionsStub::$short
 * @method string getInjected() getter for ConditionsStub::$injected
 * @method string setInjected(string $val) setter for ConditionsStub::$injected
 * @method string getInvalid() getter for ConditionsStub::$invalid
 * @method string setInvalid(string $val) setter for ConditionsStub::$invalid
 * @method string getModifier() getter for ConditionsStub::$modifier
 * @method string setModifier(string $val) setter for ConditionsStub::$modifier
 */
class ConditionsStub
{
    use Axessors;

    /** @var string with short conditions */
    public $short = 'short'; #> +wrt 1..10 +rdb 1..10
    /** @var string with injected conditions */
    public $injected = 'injected'; #> +wrt `$var === 'new injected'` +rdb `$var === 'injected'`
    /** @var string with invalid conditions */
    public $invalid = 'invalid'; #> +wrt `not a valid condition` +rdb `not a valid condition`
    /** @var string with modifying conditions */
    public $modifier = 'modifier'; #> +wrt `$var = 'new value'` +rdb `$var = 'new value'`  
}
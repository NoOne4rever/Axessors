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
 * Class CombinedConditionsStub.
 *
 * @package NoOne4rever\Axessors\Tests\Stubs
 *
 * @method void setLogicalAnd(int $val) setter for CombinedConditionsStub::$logicalAnd
 * @method void setLogicalOr(int $val) setter for CombinedConditionsStub::$logicalOr
 * @method void setBoth(int $val) setter for CombinedConditionsStub::$both
 * @method void setDifferent(int $val) setter for CombinedConditionsStub::$different
 */
class CombinedConditionsStub
{
    use Axessors;

    /** @var int with logically combined by AND conditions */
    public $logicalAnd; #: +wrt int 1..20 && != 5 && != 10
    /** @var int with logically combined by OR conditions */
    public $logicalOr; #: +wrt int 1..20 || != 100
    /** @var int with logically combined by AND and OR conditions */
    public $both; #: +wrt int 1..20 || != 5 && != 10
    /** @var int with logically combined short and injected conditions */
    public $different; #: +wrt int 1..20 && `$var != 5`
    /** @var int with grouped conditions */
    public $grouped; #: +wrt int (!= 4 && (1..10 || > 100) && `{return 'ok';}`)
}
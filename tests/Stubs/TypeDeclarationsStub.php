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
 * Class TypeDeclarationsStub.
 *
 * @package NoOne4rever\Axessors\Tests\Stubs
 *
 * @method void setSingle(string $val) setter for TypeDeclarationsStub::$single
 * @method void setMultiple(mixed $val) setter for TypeDeclarationsStub::$multiple
 * @method void setArray(mixed $val) setter for TypeDeclarationsStub::$array
 * @method void setDefault(mixed $val) setter for TypeDeclarationsStub::$default
 * @method void setClass(NonAxessorsStub $val) setter for TypeDeclarationsStub::$default
 * @method void setExtended(array $val) setter for TypeDeclarationsStub::$extended
 */
class TypeDeclarationsStub
{
    use Axessors;

    /** @var string string field */
    public $single; #: +wrt string
    /** @var mixed integer or boolean or string */
    public $multiple; #: +wrt int|bool|string
    /** @var string[] array of strings */
    public $array; #: +wrt array[string]
    /** @var int field with default type */
    public $default = 1; #: +wrt int
    /** @var NonAxessorsStub custom class */
    public $class; #: +wrt NonAxessorsStub
    /** @var array extended array */
    public $extended; #: +wrt Array
}
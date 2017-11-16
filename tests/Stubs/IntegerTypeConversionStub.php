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
 * Class IntegerTypeConversionTest.
 * 
 * @package NoOne4rever\Axessors\Tests
 * 
 * @method void setInt(int $val) setter for IntegerTypeConversionTestStub::$int
 * @method void setFloat(float $val) setter for IntegerTypeConversionTestStub::$float
 * @method void setString(string $val) setter for IntegerTypeConversionTestStub::$string
 * @method void setArray(array $val) setter for IntegerTypeConversionTestStub::$array
 * @method void setStdClass(\stdClass $val) setter for IntegerTypeConversionTestStub::$stdClass 
 */
class IntegerTypeConversionStub
{
    use Axessors;
    
    /** @var int integer field */
    public $int; #> +wrt int == 10
    /** @var float float field */
    public $float; #> +wrt float == 10
    /** @var string string field */
    public $string; #> +wrt string == 10
    /** @var array array field */
    public $array; #> +wrt array == 10
    /** @var \stdClass stdClass field */
    public $stdClass; #> +wrt \stdClass == 10
}

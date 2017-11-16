<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors\Tests;

use NoOne4rever\Axessors\Tests\Stubs\IntegerTypeConversionStub;
use PHPUnit\Framework\TestCase;

require __DIR__ . '/Stubs/IntegerTypeConversionStub.php';

/**
 * Class IntegerTypeConversionTest.
 * 
 * Tests conditional type conversion.
 * 
 * @package NoOne4rever\Axessors\Tests
 */
class IntegerTypeConversionTest extends TestCase
{
    /** @var IntegerTypeConversionStub type conversion stub */
    private $stub;

    /**
     * Creates new sub instance.
     */
    public function setUp(): void
    {
        $this->stub = new IntegerTypeConversionStub();
    }

    /**
     * Tests if integer is converted correctly.
     */
    public function testIntegerCanBeConvertedToInt(): void
    {
        $newValue = 10;
        
        $this->stub->setInt($newValue);
        
        $this->assertEquals($newValue, $this->stub->int);
    }

    /**
     * Tests if float is converted correctly.
     */
    public function testFloatCanBeConvertedToInt(): void
    {
        $newValue = 10;
        
        $this->stub->setFloat($newValue);
        
        $this->assertEquals($newValue, $this->stub->float);
    }

    /**
     * Tests if string can be converted correctly.
     */
    public function testStringCanBeConvertedToInt(): void
    {
        $newValue = '10 symbols';
        
        $this->stub->setString($newValue);
        
        $this->assertEquals($newValue, $this->stub->string);
    }

    /**
     * Tests if array can be converted correctly.
     */
    public function testArrayCanBeConvertedToInt(): void
    {
        $newValue = range(1, 10);
        
        $this->stub->setArray($newValue);
        
        $this->assertEquals($newValue, $this->stub->array);
    }

    /**
     * Tests if incorrect data type can not be converted correctly.
     * 
     * @expectedException \NoOne4rever\Axessors\Exceptions\TypeError
     */
    public function testIncorrectTypeCanNotBeConvertedToInt(): void
    {
        $newValue = new \stdClass();
        
        $this->stub->setStdClass($newValue);
    }
}

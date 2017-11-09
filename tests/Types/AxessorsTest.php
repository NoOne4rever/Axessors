<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors\Tests;

use NoOne4rever\Axessors\Axessors;
use PHPUnit\Framework\TestCase;

/**
 * Tests Axessors main trait.
 * 
 * @package NoOne4rever\Axessors
 */
class AxessorsTest extends TestCase
{
    /** @var object an object with Axessors trait */
    private $mock;

    /** Sets tests up. */
    public function setUp()
    {
        $this->mock = new class {
            use Axessors;
            
            private $field = 'smth';
            
            public function func(): string 
            {
                return 'smth';
            }
            
            public static function sFunc(): string 
            {
                return 'smth static';
            }
        };
    }

    /** Tests if an existing instance method runs correctly. */
    public function testExistingInstanceMethodRunsCorrectly(): void
    {
        $this->assertEquals('smth', $this->mock->__call('func', []));
    }

    /** Tests if an existing static method runs correctly. */
    public function testExistingStaticMethodRunsCorrectly(): void
    {
        $this->assertEquals('smth static', $this->mock::__callStatic('sFunc', []));
    }
    
    /** Tests if an Axessors method runs and changes variable's value correctly. */
    public function testVarAxessorsExecuteFunctionWorksCorrectlyForInstance(): void
    {
        $var = '';
        $code = '$var = $this->field';
        
        $var = $this->mock->__axessorsExecute($code, $var, false);
        
        $this->assertEquals('smth', $var);
    }

    /** Tests if an Axessors method runs and returns boolean value correctly. */
    public function testConstAxessorsExecuteFunctionWorksCorrectlyForInstance(): void
    {
        $var = false;
        $code = '1 == 1';
        
        $var = $this->mock->__axessorsExecute($code, $var, true);
        
        $this->assertTrue($var);
    }

    /**
     * Tests if an exception is thrown on invalid code string.
     * 
     * @expectedException \NoOne4rever\Axessors\Exceptions\ReThrownError
     */
    public function testExceptionIsThrownOnIncorrectCode(): void
    {
        $var = 0;
        $code = 'Something wrong...';
        
        $this->mock->__axessorsExecute($code, $var, false);
    }
}

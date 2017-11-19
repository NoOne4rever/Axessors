<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors\Tests;

use NoOne4rever\Axessors\Axessors;
use NoOne4rever\Axessors\AxessorsStartup;
use NoOne4rever\Axessors\CommentLexer;
use NoOne4rever\Axessors\Tests\Stubs\StubInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class Z-StartupTest.
 *
 * Tests Axessors StartupPHP.
 *
 * @package NoOne4rever\Axessors\Tests
 */
class StartupTest extends TestCase
{
    /** @var object test stub */
    private $stub;
    
    /**
     * Tests Axessors startup file.
     */
    public function testStartup(): void
    {
        AxessorsStartup::run();
        $this->assertTrue(true);
    }

    /**
     * Tests if not implemented method is processed.
     *
     * @expectedException \NoOne4rever\Axessors\Exceptions\OopError
     */
    public function testNotImplementedMethodFailure(): void
    {
        $this->stub = new class implements StubInterface
        {
            use Axessors;
        };
        AxessorsStartup::run();
    }

    /**
     * Tests if invalid default type is processed.
     *
     * @expectedException \NoOne4rever\Axessors\Exceptions\TypeError
     */
    public function testInvalidDefaultTypeFailure(): void
    {
        $this->stub = new class
        {
            use Axessors;

            public $field = false; #> +wrt int 
        };

        $this->indexClass($this->stub);
    }

    /**
     * Tests if null type declaration is processed.
     *
     * @expectedException \NoOne4rever\Axessors\Exceptions\TypeError
     */
    public function testNullTypeDeclarationFailure(): void
    {
        $this->stub = new class
        {
            use Axessors;

            public $field; #> +wrt
        };

        $this->indexClass($this->stub);
    }

    /**
     * Tests if invalid statements order is processed.
     *
     * @expectedException \NoOne4rever\Axessors\Exceptions\SyntaxError
     */
    public function testInvalidStatementsOrder(): void
    {
        $this->stub = new class
        {
            use Axessors;

            public $field; #> +rdb int +wrt
        };

        $this->indexClass($this->stub);
    }

    /**
     * Indexes given classes.
     *
     * @param object $object class instance to index
     */
    private function indexClass($object): void
    {
        $reflection = new \ReflectionClass($object);
        $lexer = new CommentLexer($reflection);
        $lexer->getClassData();
    }
}

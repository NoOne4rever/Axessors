<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors\Tests;

use NoOne4rever\Axessors\Axessors;
use NoOne4rever\Axessors\CommentLexer;
use PHPUnit\Framework\TestCase;

/**
 * Class StartupTest.
 *
 * Tests Axessors StartupPHP.
 *
 * @package NoOne4rever\Axessors\Tests
 */
class StartupTest extends TestCase
{
    /**
     * Tests Axessors startup file.
     */
    public function testStartup(): void
    {
        require __DIR__ . '/../src/Startup.php';

        $this->assertTrue(true);
    }

    /**
     * Tests if invalid default type is processed.
     *
     * @expectedException \NoOne4rever\Axessors\Exceptions\TypeError
     */
    public function testInvalidDefaultTypeFailure(): void
    {
        $stub = new class
        {
            use Axessors;

            public $field = false; #> +wrt int 
        };

        $this->indexClass($stub);
    }

    /**
     * Tests if null type declaration is processed.
     *
     * @expectedException \NoOne4rever\Axessors\Exceptions\TypeError
     */
    public function testNullTypeDeclarationFailure(): void
    {
        $stub = new class
        {
            use Axessors;

            public $field; #> +wrt
        };

        $this->indexClass($stub);
    }

    /**
     * Tests if invalid statements order is processed.
     *
     * @expectedException \NoOne4rever\Axessors\Exceptions\SyntaxError
     */
    public function testInvalidStatementsOrder(): void
    {
        $stub = new class
        {
            use Axessors;

            public $field; #> +rdb int +wrt
        };

        $this->indexClass($stub);
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

<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @package NoOne4rever\Axessors
 * @license GPL
 */

namespace NoOne4rever\Axessors;

use NoOne4rever\Axessors\Exceptions\InternalError;

/**
 * Class HierarchyLexer
 * 
 * Searches for Axessors methods in abstract classes and interfaces.
 */
class HierarchyLexer extends Lexer
{
    /** @var string[] patterns of necessary tokens */
    private $expectations;
    /** @var int[] numbers of required tokens */
    private $requiredItems;

    /**
     * HierarchyLexer constructor.
     * 
     * @param \ReflectionClass $reflection class' reflection
     * @throws InternalError if given class is not abstract or is not an interface
     */
    public function __construct(\ReflectionClass $reflection)
    {
        parent::__construct($reflection);
        if ($reflection->isInterface()) {
            $this->expectations = [
                '{^#}',
                '{^public}',
                '{^function}',
                '{^[a-zA-Z_][a-zA-Z0-9_]*}'
            ];
            $this->requiredItems = [0, 1, 2, 3];
        } elseif ($reflection->isAbstract()) {
            $this->expectations = [
                '{^#}',
                '{^abstract}',
                '{^(public|protected)}',
                '{^function}',
                '{^[a-zA-Z_][a-zA-Z0-9_]*}'
            ];
            $this->requiredItems = [0, 1, 2, 3, 4];
        } else {
            throw new InternalError("\"{$reflection->name}\" is not an interface or abstract class");
        }
    }

    /**
     * Returns Axessors methods' names.
     * 
     * @return string[] Axessors methods' names
     */
    public function getMethods(): array
    {
        $methods = [];
        for ($i = $this->startLine; $i <= $this->endLine; ++$i) {
            $this->readLine();
            if (!$this->isAxsMethod()) {
                continue;
            }
            $method = $this->getMethod();
            if ($this->reflection->isInterface()) {
                $accessModifier = $method[1];
                $methodName = $method[3];
            } else {
                $accessModifier = $method[2];
                $methodName = $method[4];
            }
            $methods[$accessModifier][] = $methodName;
        }
        return $methods;
    }

    /**
     * Returns Axessors method slit into array of tokens.
     * 
     * @return string[] found tokens
     */
    private function getMethod(): array
    {
        return $this->parse(
            $this->currentLine,
            $this->expectations,
            $this->requiredItems
        );
    }

    /**
     * Checks if the line of code given is an Axessors method declaration.
     * 
     * @return bool result of the checkout
     */
    private function isAxsMethod(): bool
    {
        return preg_match('{^\s*#}', $this->currentLine);
    }
}

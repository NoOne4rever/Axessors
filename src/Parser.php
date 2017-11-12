<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors;

use NoOne4rever\Axessors\Exceptions\{
    InternalError,
    SyntaxError
};

/**
 * Class Parser.
 *
 * Analyses tokens from the Axessors comment.
 *
 * @package NoOne4rever\Axessors
 */
class Parser
{
    private const ACCESS_MODIFIER_1 = 1;
    private const KEYWORD_1 = 2;
    private const TYPE = 3;
    private const CONDITIONS_1 = 4;
    private const HANDLERS_1 = 6;
    private const ACCESS_MODIFIER_2 = 7;
    private const KEYWORD_2 = 8;
    private const CONDITIONS_2 = 9;
    private const HANDLERS_2 = 11;
    private const ALIAS = 13;

    private const F_WRITABLE = 'writable';
    private const S_WRITABLE = 'wrt';
    private const F_ACCESSIBLE = 'accessible';
    private const S_ACCESSIBLE = 'axs';
    private const F_READABLE = 'readable';
    private const S_READABLE = 'rdb';
    private const A_PUBLIC = '+';
    private const A_PROTECTED = '~';
    private const A_PRIVATE = '-';

    /** @var string[] tokens from Axessors comment */
    private $tokens;
    /** @var \ReflectionProperty property's reflection */
    private $reflection;
    /** @var string[] access modifiers for getter and setter */
    private $accessModifiers;
    /** @var string alias of property */
    private $alias;
    /** @var bool information about order of tokens */
    private $readableFirst;

    /**
     * Parser constructor.
     *
     * @param \ReflectionProperty $reflection property's reflection
     * @param array $tokens tokens from the Axessors comment
     */
    public function __construct(\ReflectionProperty $reflection, array $tokens)
    {
        $this->reflection = $reflection;
        $this->tokens = $tokens;
        $this->readableFirst = (bool)preg_match('{^(rdb|readable)$}', $this->tokens[self::KEYWORD_1]);
        $this->validateStatements();
        $this->processAlias();
    }

    /**
     * Returns type declaration.
     * 
     * @return string type declaration
     */
    public function getTypeDef(): string 
    {
        return $this->tokens[self::TYPE] ?? '';
    }

    /**
     * Returns class namespace.
     * 
     * @return string class namespace
     */
    public function getNamespace(): string 
    {
        return $this->reflection->getDeclaringClass()->getNamespaceName();
    }

    /**
     * Getter for {@see Parser::$reflection}.
     * 
     * @return \ReflectionProperty property reflection
     */
    public function getReflection(): \ReflectionProperty
    {
        return $this->reflection;
    }

    /**
     * Returns conditions for setter.
     * 
     * @return string input conditions.
     */
    public function getInConditions(): string 
    {
        return $this->tokens[$this->readableFirst ? self::CONDITIONS_2 : self::CONDITIONS_1] ?? '';
    }

    /**
     * Returns conditions for getter.
     * 
     * @return string output conditions
     */
    public function getOutConditions(): string 
    {
        return $this->tokens[$this->readableFirst ? self::CONDITIONS_1 : self::CONDITIONS_2] ?? '';
    }

    /**
     * Returns handlers for setter.
     * 
     * @return string input handlers
     */
    public function getInHandlers(): string 
    {
        return $this->tokens[$this->readableFirst ? self::HANDLERS_2 : self::HANDLERS_1] ?? '';
    }

    /**
     * Returns handlers for getter.
     * 
     * @return string output handlers
     */
    public function getOutHandlers(): string
    {
        return $this->tokens[$this->readableFirst ? self::HANDLERS_1 : self::HANDLERS_2] ?? '';
    }
    
    /**
     * Returns property's alias.
     *
     * @return string property's alias
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * Processes access modifiers for getter and setter.
     *
     * @return string[] access modifiers
     */
    public function processAccessModifier(): array
    {
        $type = $this->getKeyword(self::KEYWORD_1);
        if ($type == 'access') {
            $this->accessModifiers = [
                'write' => $this->replaceSignWithWord($this->tokens[self::ACCESS_MODIFIER_1]),
                'read' => $this->replaceSignWithWord($this->tokens[self::ACCESS_MODIFIER_1])
            ];
            return $this->accessModifiers;
        }
        $this->accessModifiers[$type] = $this->replaceSignWithWord($this->tokens[self::ACCESS_MODIFIER_1]);
        if (isset($this->tokens[self::KEYWORD_2])) {
            $type = $this->getKeyword(self::KEYWORD_2);
            $this->accessModifiers[$type] = $this->replaceSignWithWord($this->tokens[self::ACCESS_MODIFIER_2]);
        }
        return $this->accessModifiers;
    }

    /**
     * Processes property's alias.
     */
    private function processAlias(): void
    {
        $this->alias = $this->tokens[self::ALIAS] ?? $this->reflection->name;
    }

    /**
     * Turns short style of access modifier to the full keyword.
     *
     * @param string $sign access modifier sign
     * @return string access modifier
     * @throws InternalError if access modifier is invalid
     */
    private function replaceSignWithWord(string $sign): string
    {
        switch ($sign) {
            case self::A_PUBLIC:
                return 'public';
            case self::A_PROTECTED:
                return 'protected';
            case self::A_PRIVATE:
                return 'private';
            default:
                throw new InternalError('not a valid access modifier given');
        }
    }

    /**
     * Validates order of statements in Axessors comment.
     *
     * @throws SyntaxError if the statements go in incorrect order
     */
    private function validateStatements(): void
    {
        if (isset($this->tokens[self::KEYWORD_2])) {
            if ($this->tokens[self::KEYWORD_1] == $this->tokens[self::KEYWORD_2]) {
                throw new SyntaxError("the same statements in {$this->reflection->getDeclaringClass()->name}::\${$this->reflection->name} Axessors comment");
            } elseif (preg_match('{^(wrt|writable)$}', $this->tokens[self::KEYWORD_2])) {
                throw new SyntaxError(
                    "\"writable\" statement must be the first in {$this->reflection->getDeclaringClass()->name}::\${$this->reflection->name} Axessors comment\""
                );
            }
        }
    }

    /**
     * Returns normalized keyword with type of access.
     *
     * @param int $token token
     * @return string keyword
     * @throws InternalError if the token with keyword is not valid
     */
    private function getKeyword(int $token): string
    {
        if (preg_match(sprintf('{^(%s|%s)$}', self::F_ACCESSIBLE, self::S_ACCESSIBLE), $this->tokens[$token])) {
            return 'access';
        } elseif (preg_match(sprintf('{^(%s|%s)$}', self::F_WRITABLE, self::S_WRITABLE), $this->tokens[$token])) {
            return 'write';
        } elseif (preg_match(sprintf('{^(%s|%s)$}', self::F_READABLE, self::S_READABLE), $this->tokens[$token])) {
            return 'read';
        } else {
            throw new InternalError('not a valid keyword token given');
        }
    }
}

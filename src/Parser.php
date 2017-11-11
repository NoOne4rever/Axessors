<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors;

use NoOne4rever\Axessors\Exceptions\InternalError;
use NoOne4rever\Axessors\Exceptions\SyntaxError;

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
    /** @var string class' namespace */
    private $namespace;
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
        $this->namespace = $reflection->getDeclaringClass()->getNamespaceName();
        $this->readableFirst = (bool)preg_match('{^(rdb|readable)$}', $this->tokens[self::KEYWORD_1]);
        $this->validateStatements();
        $this->processAlias();
    }
    
    public function getTypeDef(): string 
    {
        return $this->tokens[self::TYPE];
    }
    
    public function getNamespace(): string 
    {
        return $this->namespace;
    }
    
    public function getReflection(): \ReflectionProperty
    {
        return $this->reflection;
    }

    public function getInConditions(): string 
    {
        return $this->tokens[$this->readableFirst ? self::CONDITIONS_2 : self::CONDITIONS_1] ?? '';
    }
    
    public function getOutConditions(): string 
    {
        return $this->tokens[$this->readableFirst ? self::CONDITIONS_1 : self::CONDITIONS_2] ?? '';
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
     * Generates list of methods for property.
     *
     * @param array $typeTree type tree
     * 
     * @return string[] methods' names
     */
    public function processMethods(array $typeTree): array
    {
        $methods = [];
        $name = $this->alias ?? $this->reflection->name;

        if (isset($this->accessModifiers['read'])) {
            $methods[$this->accessModifiers['read']][] = 'get' . ucfirst($name);
        }
        if (isset($this->accessModifiers['write'])) {
            $methods[$this->accessModifiers['write']][] = 'set' . ucfirst($name);
        }

        foreach ($typeTree as $index => $type) {
            $class = is_int($index) ? $type : $index;
            try {
                class_exists($class);
            } catch (InternalError $error) {
                continue;
            }
            foreach ((new \ReflectionClass($class))->getMethods() as $method) {
                $isAccessible = $method->isStatic() && $method->isPublic() && !$method->isAbstract();
                if ($isAccessible && preg_match('{^m_(in|out)_.*?PROPERTY.*}', $method->name)) {
                    if (substr($method->name, 0, 5) == 'm_in_' && isset($this->accessModifiers['write'])) {
                        $methods[$this->accessModifiers['write']][] = str_replace('PROPERTY', ucfirst($name),
                            substr($method->name, 5));
                    } elseif (substr($method->name, 0, 6) == 'm_out_' && isset($this->accessModifiers['read'])) {
                        $methods[$this->accessModifiers['read']][] = str_replace('PROPERTY', ucfirst($name),
                            substr($method->name, 6));
                    }
                }
            }
        }
        return $methods;
    }

    /**
     * Creates list of handlers for input data.
     *
     * @return string[] handlers
     */
    public function processInputHandlers(): array
    {
        return $this->processTokens(!$this->readableFirst, self::HANDLERS_1, self::HANDLERS_2,
            [$this, 'makeHandlersList']);
    }

    /**
     * Creates list of handlers for output data.
     *
     * @return string[] handlers
     */
    public function processOutputHandlers(): array
    {
        return $this->processTokens($this->readableFirst, self::HANDLERS_1, self::HANDLERS_2,
            [$this, 'makeHandlersList']);
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
     * Creates list of handlers from a string of handlers definition.
     *
     * @param string $handlers handlers
     * @return string[] handlers
     */
    private function makeHandlersList(string $handlers): array
    {
        $result = preg_replace_callback(
            '{`([^`]|\\\\`)+((?<!\\\\)`)}',
            function (array $matches) {
                return addcslashes($matches[0], ',');
            },
            $handlers
        );
        $result = preg_split('{(?<!\\\\),\s*}', $result);
        foreach ($result as &$handler) {
            $injProcessor = new InjectedStringParser(stripcslashes($handler));
            $handler = $injProcessor->resolveClassNames($this->namespace);
        }
        return $result;
    }

    /**
     * Processes tokens.
     *
     * @param bool $mode a flag; mode of execution
     * @param int $token1 first token
     * @param int $token2 second token
     * @param callable $callback special callback
     * @return string[] normalized array of Axessors tokens
     */
    private function processTokens(bool $mode, int $token1, int $token2, callable $callback): array
    {
        if ($mode && isset($this->tokens[$token1])) {
            return $callback($this->tokens[$token1]);
        } elseif (!$mode && isset($this->tokens[$token2])) {
            return $callback($this->tokens[$token2]);
        } else {
            return [];
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

<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @package Axessors
 * @license GPL
 */

namespace Axessors;

use Axessors\Exceptions\InternalError;
use Axessors\Exceptions\SyntaxError;
use Axessors\Exceptions\TypeError;

/**
 * Class Parser.
 * 
 * Analyses tokens from the Axessors comment.
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
    /** @var array type tree */
    private $typeTree = [];

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
        $this->readableFirst = preg_match('{^(rdb|readable)$}', $this->tokens[self::KEYWORD_1]);
        $this->validateStatements();
        $this->processAlias();
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
     * @return string[] methods' names
     */
    public function processMethods(): array
    {
        $methods = [];
        $name = $this->alias ?? $this->reflection->name;

        if (isset($this->accessModifiers['read'])) {
            $methods[$this->accessModifiers['read']][] = 'get' . ucfirst($name);
        }
        if (isset($this->accessModifiers['write'])) {
            $methods[$this->accessModifiers['write']][] = 'set' . ucfirst($name);
        }

        foreach ($this->typeTree as $index => $type) {
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
     * Creates list of conditions for input data.
     * 
     * @return string[] conditions
     */
    public function processInputConditions(): array
    {
        return $this->processConditions(!$this->readableFirst);
    }

    /**
     * Creates list of conditions for output data.
     * 
     * @return string[] conditions
     */
    public function processOutputConditions(): array
    {
        return $this->processConditions($this->readableFirst);
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
     * Creates type tree.
     * 
     * @return array
     * @throws TypeError if type defined in Axessors comment does not match default type of property
     */
    public function processType(): array
    {
        $type = $this->getDefaultType();
        if (isset($this->tokens[self::TYPE])) {
            $this->typeTree = $this->makeTypeTree($this->tokens[self::TYPE]);
            if ($type != 'NULL') {
                foreach ($this->typeTree as $treeType => $subType) {
                    if (is_int($treeType)) {
                        $treeType = $subType;
                    }
                    if ($type == $treeType || "{$type}_ext" == $treeType) {
                        $this->validateTypeTree($this->typeTree);
                        return $this->typeTree;
                    }
                }
                throw new TypeError(
                    "type in Axessors comment for {$this->reflection->getDeclaringClass()->name}::\${$this->reflection->name} "
                    . "does not equal default type of property"
                );
            }
        } else {
            if ($type == 'NULL') {
                var_dump($type);
                var_dump($this->tokens);
                throw new TypeError('type not defined');
            } else {
                $this->typeTree = [$type];
            }
        }
        $this->validateTypeTree($this->typeTree);
        return $this->typeTree;
    }

    /**
     * Returns default type of property.
     * 
     * @return string type
     */
    private function getDefaultType(): string
    {
        if ($this->reflection->isStatic()) {
            $this->reflection->setAccessible(true);
            $type = $this->replacePhpTypeWithAxsType(gettype($this->reflection->getValue()));
            $this->reflection->setAccessible(false);
        } else {
            $properties = $this->reflection->getDeclaringClass()->getDefaultProperties();
            $type = isset($properties[$this->reflection->name]) ? $this->replacePhpTypeWithAxsType($this->getType($properties[$this->reflection->name]))
                : 'NULL';
        }
        return $type;
    }

    /**
     * Checks if the class defined in the current namespace and fixes class' name.
     * 
     * @param string $class class' name
     * @return string full name of class
     */
    private function validateType(string $class): string
    {
        try {
            class_exists($class);
            return $class;
        } catch (InternalError $error) {
            $class = "$this->namespace\\$class";
            class_exists($class);
            return $class;
        }
    }

    /**
     * Validates type tree.
     * 
     * @param array $tree type tree
     * @throws TypeError the type is not iterateable, but it is defined as array-compatible type
     */
    private function validateTypeTree(array $tree): void
    {
        foreach ($tree as $type => $subtype) {
            if (!is_int($type)) {
                if (!is_subclass_of($type, 'Axessors\Types\Iterateable')) {
                    throw new TypeError("\"$type\" is not iterateable {$this->reflection->getDeclaringClass()->name}::\${$this->reflection->name} Axessors comment");
                }
            }
        }
    }

    /**
     * Processes property's alias.
     */
    private function processAlias(): void
    {
        $this->alias = $this->tokens[self::ALIAS] ?? $this->reflection->name;
    }

    /**
     * Makes type tree form type's string.
     * 
     * @param string $typeDefinition type definition
     * @return array type tree
     */
    private function makeTypeTree(string $typeDefinition): array
    {
        $typeTree = [];
        $typeDefinition = explode('%', $this->replaceSensibleDelimiters($typeDefinition));
        foreach ($typeDefinition as $type) {
            if (($bracket = strpos($type, '[')) !== false) {
                $subtype = substr($type, $bracket + 1, strlen($type) - $bracket - 2);
                $type = substr($type, 0, $bracket);
                $type = $this->validateType($this->replacePhpTypeWithAxsType($type));
                $typeTree[$type] = $this->makeTypeTree($subtype);
            } else {
                $type = $this->validateType($this->replacePhpTypeWithAxsType($type));
                $typeTree[] = $type;
            }
        }
        return $typeTree;
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
     * Returns type of variable.
     * 
     * @param $var mixed variable
     * @return string type of variable
     */
    private function getType($var): string
    {
        if (is_callable($var)) {
            return 'callable';
        }
        $type = gettype($var);
        return $type == 'integer' ? 'int' : $type;
    }

    /**
     * Replaces internal PHP type with an Axessors type.
     * 
     * @param string $type type
     * @return string axessors type
     */
    private function replacePhpTypeWithAxsType(string $type): string
    {
        $_type = lcfirst($type);
        switch ($_type) {
            case 'int':
            case 'float':
            case 'bool':
            case 'string':
            case 'array':
            case 'object':
            case 'resource':
            case 'callable':
            case 'mixed':
                $type = "Axessors\\Types\\axs_{$_type}" . ($type !== $_type ? '_ext' : '');
        }
        return $type;
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
            $handler = stripcslashes($handler);
        }
        return $result;
    }

    /**
     * Creates list of conditions from a string of conditions definition.
     * 
     * @param string $conditions conditions
     * @return string[] conditions
     */
    private function explodeConditions(string $conditions): array
    {
        $result = [];
        $conditions = preg_replace_callback(
            '{`([^`]|\\\\`)+((?<!\\\\)`)}',
            function (array $matches) {
                return addcslashes($matches[0], '&|');
            },
            $conditions
        );
        $conditions = preg_split('{\s*\|\|\s*}', $conditions);
        foreach ($conditions as $condition) {
            $result[] = preg_split('{\s*&&\s*}', $condition);
        }
        foreach ($result as $number => &$complexCondition) {
            if (is_array($complexCondition)) {
                foreach ($complexCondition as $num => &$condition) {
                    $condition = stripcslashes($condition);
                }
            } else {
                $complexCondition = stripcslashes($complexCondition);
            }
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
     * Processes conditions.
     * 
     * @param bool $mode mode of execution
     * @return string[] conditions
     */
    private function processConditions(bool $mode): array
    {
        return $this->processTokens($mode, self::CONDITIONS_1, self::CONDITIONS_2, [$this, 'makeConditionsTree']);
    }

    /**
     * Makes tree of conditions.
     * 
     * @param string $conditions string with conditions definition
     * @return array tree of conditions
     */
    private function makeConditionsTree(string $conditions): array
    {
        $result = [];
        $conditions = $this->explodeConditions($conditions);
        foreach ($conditions as $number => $condition) {
            foreach ($condition as $token) {
                if (count($condition) === 1) {
                    $result[] = $token;
                } else {
                    $result[$number][] = $token;
                }
            }
        }
        return $result;
    }

    /**
     * Replaces type delimiters in type definition.
     * 
     * @param string $subject string with type definition
     * @return string with replaces delimiters
     */
    private function replaceSensibleDelimiters(string $subject): string
    {
        $length = strlen($subject);
        $brackets = 0;
        for ($i = 0; $i < $length; ++$i) {
            if ($subject{$i} == '[') {
                ++$brackets;
            } elseif ($subject{$i} == ']') {
                --$brackets;
            } elseif ($subject{$i} == '|' && !$brackets) {
                $subject{$i} = '%';
            }
        }
        return $subject;
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

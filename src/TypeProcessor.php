<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors;

use NoOne4rever\Axessors\Exceptions\TypeError;
use NoOne4rever\Axessors\Types\axs_bool;
use NoOne4rever\Axessors\Types\axs_float;
use NoOne4rever\Axessors\Types\axs_int;
use NoOne4rever\Axessors\Types\axs_mixed;

/**
 * Class TypeProcessor.
 * 
 * Processes property's type.
 * 
 * @package NoOne4rever\Axessors
 */
class TypeProcessor
{
    /** @var \ReflectionProperty reflection */
    private $reflection;
    private $namespace;
    
    private $typeDeclaration;    
    private $typeTree;
    
    public function __construct(\ReflectionProperty $reflection, string $namespace, string $typeDeclaration)
    {
        $this->reflection = $reflection;
        $this->namespace = $namespace;
        $this->typeDeclaration = $typeDeclaration;
    }

    public function getTypeTree(): array 
    {
        return $this->typeTree;
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
        if (isset($this->typeDeclaration)) {
            $this->typeTree = $this->makeTypeTree($this->typeDeclaration);
            if ($type != 'NULL') {
                foreach ($this->typeTree as $treeType => $subType) {
                    if (is_int($treeType)) {
                        $treeType = $subType;
                    }
                    if ($type === $treeType || "{$type}_ext" === $treeType || axs_mixed::class === $treeType) {
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
        if ($class[0] === '\\' or class_exists($class)) {
            return $class;
        } else {
            return "{$this->namespace}\\$class";
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
                if (!is_subclass_of($type, 'NoOne4rever\Axessors\Types\Iterateable')) {
                    throw new TypeError("\"$type\" is not iterateable {$this->reflection->getDeclaringClass()->name}::\${$this->reflection->name} Axessors comment");
                }
            }
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
            case 'boolean':
            case 'bool':
                $_type = axs_bool::class;
                break;
            case 'double':
            case 'float':
                $_type = axs_float::class;
                break;
            case 'int':
            case 'integer':
                $_type = axs_int::class;
                break;
            case 'string':
            case 'array':
            case 'object':
            case 'resource':
            case 'callable':
            case 'mixed':
                $_type = "NoOne4rever\\Axessors\\Types\\axs_{$_type}";
                break;
            default:
                return $type;
        }
        if ($type !== lcfirst($type)) {
            $_type .= '_ext';
        }
        return $_type;
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
}
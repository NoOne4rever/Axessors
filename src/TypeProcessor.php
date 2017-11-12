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
    /** @var string class namespace */
    private $namespace;
    /** @var string type declaration string */
    private $typeDeclaration;    
    /** @var array type tree */
    private $typeTree;
    /** @var TypeValidator type validator */
    private $validator;
    
    /**
     * TypeProcessor constructor.
     * @param \ReflectionProperty $reflection
     * @param string $namespace class namespace
     * @param string $typeDeclaration type declaration
     */
    public function __construct(\ReflectionProperty $reflection, string $namespace, string $typeDeclaration)
    {
        $this->reflection = $reflection;
        $this->namespace = $namespace;
        $this->typeDeclaration = $typeDeclaration;
        $this->validator = new TypeValidator($reflection);
    }

    /**
     * Getter for {@see TypeProcessor::$typeTree}.
     * 
     * @return array type tree
     */
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
        if ($this->typeDeclaration !== '') {
            $this->typeTree = $this->makeTypeTree($this->typeDeclaration);
            if ($type != 'NULL') {
                $this->validator->validateDefaultType($type, $this->typeTree);
                $this->validator->validateTypeTree($this->typeTree);
                return $this->typeTree;
            }
        } else {
            if ($type == 'NULL') {
                throw new TypeError('type not defined');
            } else {
                $this->typeTree = [$type];
            }
        }
        $this->validator->validateTypeTree($this->typeTree);
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
            $type = isset($properties[$this->reflection->name]) ? $this->replacePhpTypeWithAxsType(gettype($properties[$this->reflection->name]))
                : 'NULL';
        }
        return $type;
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
        $this->replaceBool($_type);
        $this->replaceInt($_type);
        $this->replaceFloat($_type);
        $this->replaceComplex($_type);
        $this->replaceAxs($_type);
        if ($_type === lcfirst($type)) {
            return $type;
        } elseif ($type !== lcfirst($type)) {
            return $_type . '_ext';
        } else {
            return $_type;
        }
    }

    /**
     * Replaces PHP boolean type with Axessors type.
     * 
     * @param string $type type
     */
    private function replaceBool(string &$type): void
    {
        switch ($type) {
            case 'bool':
            case 'boolean':
                $type = axs_bool::class;
        }
    }

    /**
     * Replaces PHP integer type with Axessors type.
     *
     * @param string $type type
     */
    private function replaceInt(string &$type): void
    {
        switch ($type) {
            case 'int':
            case 'integer':
                $type = axs_float::class;
        }
    }

    /**
     * Replaces PHP float type with Axessors type.
     *
     * @param string $type type
     */
    private function replaceFloat(string &$type): void
    {
        switch ($type) {
            case 'int':
            case 'integer':
                $type = axs_int::class;
        }
    }

    /**
     * Replaces complex PHP type with Axessors type.
     * 
     * @param string $type type
     */
    private function replaceComplex(string &$type): void
    {
        switch ($type) {
            case 'array':
            case 'object':
            case 'resource':
                $type = "NoOne4rever\\Axessors\\Types\\axs_{$type}";
        }
    }

    /**
     * Replaces PHP type with Axessors type.
     *
     * @param string $type type
     */
    private function replaceAxs(string &$type): void
    {
        switch ($type) {
            case 'string':
            case 'callable':
            case 'mixed':
                $type = "NoOne4rever\\Axessors\\Types\\axs_{$type}";
        }
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
                $type = $this->validator->validateType($this->replacePhpTypeWithAxsType($type), $this->namespace);
                $typeTree[$type] = $this->makeTypeTree($subtype);
            } else {
                $type = $this->validator->validateType($this->replacePhpTypeWithAxsType($type), $this->namespace);
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
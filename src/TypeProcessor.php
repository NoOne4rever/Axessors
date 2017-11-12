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
            $typeResolver = new TypeResolver(gettype($this->reflection->getValue()));
            $type = $typeResolver->replacePhpTypeWithAxsType();
            $this->reflection->setAccessible(false);
        } else {
            $properties = $this->reflection->getDeclaringClass()->getDefaultProperties();
            $typeResolver = new TypeResolver(gettype($properties[$this->reflection->name]));
            $type = isset($properties[$this->reflection->name]) ? $typeResolver->replacePhpTypeWithAxsType()
                : 'NULL';
        }
        return $type;
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
                $typeResolver = new TypeResolver($type);
                $type = $this->validator->validateType($typeResolver->replacePhpTypeWithAxsType(), $this->namespace);
                $typeTree[$type] = $this->makeTypeTree($subtype);
            } else {
                $typeResolver = new TypeResolver($type);
                $type = $this->validator->validateType($typeResolver->replacePhpTypeWithAxsType(), $this->namespace);
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
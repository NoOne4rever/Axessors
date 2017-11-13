<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors;

use NoOne4rever\Axessors\Exceptions\TypeError;

/**
 * Class TypeProcessor.
 *
 * Processes property's type.
 *
 * @package NoOne4rever\Axessors
 */
class TypeProcessor extends TypeSuit
{
    /** @var string type declaration string */
    private $typeDeclaration;

    /**
     * TypeProcessor constructor.
     * @param \ReflectionProperty $reflection
     * @param string $namespace class namespace
     * @param string $typeDeclaration type declaration
     */
    public function __construct(\ReflectionProperty $reflection, string $namespace, string $typeDeclaration)
    {
        parent::__construct($reflection, $namespace);
        $this->typeDeclaration = $typeDeclaration;
    }

    /**
     * Creates type tree.
     *
     * @throws TypeError if type defined in Axessors comment does not match default type of property
     */
    public function processType(): void
    {
        $type = $this->getDefaultType();
        if ($this->typeDeclaration !== '') {
            $this->typeTree = $this->makeTypeTree($this->typeDeclaration);
            $validator = new TypeValidator($this->reflection, $this->namespace, $this->typeTree);
            $validator->validateTypeTree();
            if ($type != 'NULL') {
                $validator->validateDefaultType($this->replacePhpTypeWithAxsType($type));
            }
        } else {
            if ($type == 'NULL') {
                throw new TypeError('type not defined');
            } else {
                $validator = new TypeValidator($this->reflection, $this->namespace, [$type]);
                $validator->validateTypeTree();
            }
        }
        $this->typeTree = $validator->getTypeTree();
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
                $typeTree[$type] = $this->makeTypeTree($subtype);
            } else {
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
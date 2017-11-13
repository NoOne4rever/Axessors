<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors;

use NoOne4rever\Axessors\{
    Exceptions\TypeError, Types\axs_mixed
};

/**
 * Class TypeValidator.
 *
 * Validates Axessors type tree.
 *
 * @package NoOne4rever\Axessors
 */
class TypeValidator extends TypeSuit
{
    public function __construct(\ReflectionProperty $reflection, string $namespace, array $typeTree)
    {
        parent::__construct($reflection, $namespace);
        $this->typeTree = $typeTree;
    }

    public function validateTypeTree(): void
    {
        $this->typeTree = $this->validateTree($this->typeTree);
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
     * @param array $typeTree type tree
     * @return array validated type tree
     * @throws TypeError the type is not iterateable, but it is defined as array-compatible type
     */
    private function validateTree(array $typeTree): array
    {
        foreach ($typeTree as $type => $subtype) {
            if (!is_int($type)) {
                unset($typeTree[$type]);
                $type = $this->validateType($this->replacePhpTypeWithAxsType($type));
                $typeTree[$type] = $this->validateTree($subtype);
                if (!is_subclass_of($type, 'NoOne4rever\Axessors\Types\Iterateable')) {
                    throw new TypeError("\"$type\" is not iterateable {$this->reflection->getDeclaringClass()->name}::\${$this->reflection->name} Axessors comment");
                }
            } else {
                $typeTree[$type] = $this->validateType($this->replacePhpTypeWithAxsType($subtype));
            }
        }
        return $typeTree;
    }

    /**
     * Validates default type of field.
     *
     * @param string $type default type
     * @throws TypeError if default type of filed is not valid
     */
    public function validateDefaultType(string $type): void
    {
        foreach ($this->typeTree as $treeType => $subType) {
            if (is_int($treeType)) {
                $treeType = $subType;
            }
            if ($type === $treeType || "{$type}_ext" === $treeType || axs_mixed::class === $treeType) {
                return;
            }
        }
        throw new TypeError(
            "type in Axessors comment for {$this->reflection->getDeclaringClass()->name}::\${$this->reflection->name} "
            . "does not equal default type of property"
        );
    }
}
<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors;

use NoOne4rever\Axessors\{
    Exceptions\TypeError,
    Types\axs_mixed
};

/**
 * Class TypeValidator.
 *
 * Validates Axessors type tree.
 *
 * @package NoOne4rever\Axessors
 */
class TypeValidator
{
    /** @var \ReflectionProperty property reflection */
    private $reflection;
    
    /**
     * TypeValidator constructor.
     * 
     * @param \ReflectionProperty $reflection
     */
    public function __construct(\ReflectionProperty $reflection)
    {
        $this->reflection = $reflection;
    }


    /**
     * Checks if the class defined in the current namespace and fixes class' name.
     *
     * @param string $class class' name
     * @param string $namespace class namespace
     * @return string full name of class
     */
    public function validateType(string $class, string $namespace): string
    {
        if ($class[0] === '\\' or class_exists($class)) {
            return $class;
        } else {
            return "{$namespace}\\$class";
        }
    }

    /**
     * Validates type tree.
     *
     * @param array $tree type tree
     * @throws TypeError the type is not iterateable, but it is defined as array-compatible type
     */
    public function validateTypeTree(array $tree): void
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
     * Validates default type of field.
     *
     * @param string $type default type
     * @param array $typeTree type tree
     * @throws TypeError if default type of filed is not valid
     */
    public function validateDefaultType(string $type, array $typeTree): void
    {
        foreach ($typeTree as $treeType => $subType) {
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
<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @package NoOne4rever\Axessors
 * @license GPL
 */

namespace NoOne4rever\Axessors;

use NoOne4rever\Axessors\Exceptions\{
    InternalError,
    OopError
};

$numOfPredefinedClasses = 132;

$classes = get_declared_classes();
$totalClasses = count($classes);

for ($i = $numOfPredefinedClasses; $i < $totalClasses; ++$i) {
    $reflection = $_reflection = new \ReflectionClass($classes[$i]);
    if (!in_array(Axessors::class, $reflection->getTraitNames()) && !in_array(Axs::class,
            $reflection->getTraitNames())) {
        continue;
    }
    $lexer = new CommentLexer($reflection);
    $classData = $lexer->getClassData();
    Data::addClass($reflection->name, $classData);
    $requiredMethods = $implementedMethods = [];
    foreach ($reflection->getInterfaces() as $interface) {
        $requiredMethods = array_merge_recursive($requiredMethods, (new HierarchyLexer($interface))->getMethods());
    }
    while (true) {
        if ($reflection === false) {
            break;
        }
        $traits = $reflection->getTraitNames();
        $usesAxs = in_array(Axs::class, $traits) || in_array(Axessors::class, $traits);
        if (!$usesAxs) {
            break;
        }
        try {
            $methods = Data::getClass($reflection->name)->getAllMethods(true);
        } catch (InternalError $error) {
            $methods = (new CommentLexer($reflection))->getClassData()->getAllMethods(true);
        }
        $implementedMethods = array_merge_recursive($implementedMethods, $methods);
        if ($reflection->isAbstract()) {
            $requiredMethods = array_merge_recursive($requiredMethods, (new HierarchyLexer($reflection))->getMethods());
        }
        $reflection = $reflection->getParentClass();
    }
    if ($_reflection->isAbstract()) {
        continue;
    }
    foreach ($requiredMethods as $accessModifier => $methods) {
        foreach ($methods as $method) {
            if (!isset($implementedMethods[$accessModifier]) || !in_array($method,
                    $implementedMethods[$accessModifier])) {
                throw new OopError("class {$_reflection->name} does not implement required method \"$method\"");
            }
        }
    }
}

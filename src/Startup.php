<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @package Axessors
 * @license GPL
 */

namespace NoOne4rever\Axessors;

use NoOne4rever\Axessors\Exceptions\InternalError;
use NoOne4rever\Axessors\Exceptions\OopError;

const NUM_OF_PREDEFINED_CLASSES = 132;

require_once __DIR__ . '\autoload.php';

$classes = get_declared_classes();
$totalClasses = count($classes);

$data = Data::getInstance();

for ($i = NUM_OF_PREDEFINED_CLASSES; $i < $totalClasses; ++$i) {
    $reflection = $_reflection = new \ReflectionClass($classes[$i]);
    if (!in_array(Axessors::class, $reflection->getTraitNames()) && !in_array(Axs::class,
            $reflection->getTraitNames())) {
        continue;
    }
    $lexer = new CommentLexer($reflection);
    $classData = $lexer->getClassData();
    $data->addClass($reflection->name, $classData);
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
            $methods = $data->getClass($reflection->name)->getAllMethods(true);
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

<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors;

use NoOne4rever\Axessors\Exceptions\InternalError;
use NoOne4rever\Axessors\Exceptions\OopError;

/**
 * Class AxessorsStartup.
 *
 * Starts parsing classes with Axessors traits.
 *
 * @package NoOne4rever\Axessors
 */
class AxessorsStartup
{
    private const PREDEFINED_CLASSES = 132;

    public static function run(): void
    {
        $declaredClasses = get_declared_classes();
        $totalNumber = count($declaredClasses);
        for ($i = self::PREDEFINED_CLASSES; $i < $totalNumber; ++$i) {
            $reflection = new \ReflectionClass($declaredClasses[$i]);
            if (!self::isAxessorsClass($reflection)) {
                continue;
            }
            self::parseClass($reflection);
            if ($reflection->isAbstract()) {
                continue;
            }
            self::processMethods($reflection);
        }
    }

    private static function isAxessorsClass(\ReflectionClass $reflection): bool
    {
        $traits = $reflection->getTraitNames();
        return in_array(Axessors::class, $traits) || in_array(Axs::class, $traits);
    }

    private static function parseClass(\ReflectionClass $reflection): void
    {
        $lexer = new CommentLexer($reflection);
        $classData = $lexer->getClassData();
        Data::addClass($classData);
    }

    private static function processMethods(\ReflectionClass $reflection): void
    {
        $totalRequired = $totalImplemented = [];
        $reflectionBackup = $reflection;
        do {
            if (!self::isAxessorsClass($reflection)) {
                break;
            }
            $required = self::getRequiredMethods($reflection);
            $totalRequired = array_merge_recursive($totalRequired, $required);
            $implemented = self::getImplementedMethods($reflection);
            $totalImplemented = array_merge_recursive($totalImplemented, $implemented);
            $reflection = $reflection->getParentClass();
        } while ($reflection !== false);
        if (!self::areMethodsImplemented($totalRequired, $totalImplemented)) {
            throw new OopError("class {$reflectionBackup->name} does not implement required methods");
        }
    }

    private static function areMethodsImplemented(array $required, array $implemented): bool
    {
        foreach ($required as $modifier => $methods) {
            foreach ($methods as $method) {
                if (!isset($implemented[$modifier]) || !in_array($method, $implemented[$modifier])) {
                    return false;
                }
            }
        }
        return true;
    }

    private static function getImplementedMethods(\ReflectionClass $reflection): array
    {
        try {
            $classData = Data::getClass($reflection->name);
        } catch (InternalError $error) {
            $lexer = new CommentLexer($reflection);
            $classData = $lexer->getClassData();
        }
        return $classData->getAllMethods(true);
    }

    private static function getRequiredMethods(\ReflectionClass $reflection): array
    {
        $requiredInterface = self::getInterfaceMethods($reflection);
        $requiredAbstract = self::getAbstractParentMethods($reflection);
        return array_merge_recursive($requiredAbstract, $requiredInterface);
    }

    private static function getInterfaceMethods(\ReflectionClass $reflection): array
    {
        $required = [];
        foreach ($reflection->getInterfaces() as $interface) {
            $lexer = new HierarchyLexer($interface);
            $requiredByInterface = $lexer->getMethods();
            $required = array_merge_recursive($required, $requiredByInterface);
        }
        return $required;
    }

    private static function getAbstractParentMethods(\ReflectionClass $reflection): array
    {
        $required = [];
        if ($reflection->isAbstract()) {
            $lexer = new HierarchyLexer($reflection);
            $required = $lexer->getMethods();
        }
        return $required;
    }
}
<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors;

use NoOne4rever\Axessors\Types\{
    axs_bool,
    axs_float,
    axs_int
};

/**
 * Class TypeSuit.
 *
 * Provides common type tree processing functionality.
 * 
 * @package NoOne4rever\Axessors
 */
abstract class TypeSuit
{
    /** @var \ReflectionProperty property reflection */
    protected $reflection;
    /** @var array type tree */
    protected $typeTree;
    /** @var string class namespace */
    protected $namespace;
    /** @var string type */
    private $type;

    /**
     * TypeSuit constructor.
     *
     * @param \ReflectionProperty $reflection property reflection
     * @param string $ns class namespace
     */
    public function __construct(\ReflectionProperty $reflection, string $ns)
    {
        $this->reflection = $reflection;
        $this->namespace = $ns;
    }

    /**
     * Returns processed type tree.
     *
     * @return array type tree
     */
    public function getTypeTree(): array
    {
        return $this->typeTree;
    }

    /**
     * Replaces internal PHP type with an Axessors type.
     *
     * @param string $type type
     * @return string axessors type
     */
    public function replacePhpTypeWithAxsType(string $type): string
    {
        $this->type = $type;
        $_type = $this->type;
        $this->type = lcfirst($this->type);
        $this->replaceBool();
        $this->replaceInt();
        $this->replaceFloat();
        $this->replaceComplex();
        $this->replaceAxs();
        if ($_type === lcfirst($_type)) {
            return $this->type;
        } elseif ($this->type !== lcfirst($_type)) {
            return ucfirst($this->type) . '_ext';
        } else {
            return ucfirst($this->type);
        }
    }

    /**
     * Replaces PHP boolean type with Axessors type.
     */
    private function replaceBool(): void
    {
        switch ($this->type) {
            case 'bool':
            case 'boolean':
                $this->type = axs_bool::class;
        }
    }

    /**
     * Replaces PHP integer type with Axessors type.
     */
    private function replaceInt(): void
    {
        switch ($this->type) {
            case 'int':
            case 'integer':
                $this->type = axs_float::class;
        }
    }

    /**
     * Replaces PHP float type with Axessors type.
     */
    private function replaceFloat(): void
    {
        switch ($this->type) {
            case 'int':
            case 'integer':
                $this->type = axs_int::class;
        }
    }

    /**
     * Replaces complex PHP type with Axessors type.
     */
    private function replaceComplex(): void
    {
        switch ($this->type) {
            case 'array':
            case 'object':
            case 'resource':
                $this->type = "NoOne4rever\\Axessors\\Types\\axs_{$this->type}";
        }
    }

    /**
     * Replaces PHP type with Axessors type.
     */
    private function replaceAxs(): void
    {
        switch ($this->type) {
            case 'string':
            case 'callable':
            case 'mixed':
                $this->type = "NoOne4rever\\Axessors\\Types\\axs_{$this->type}";
        }
    }
}
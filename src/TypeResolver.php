<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors;

use NoOne4rever\Axessors\Types\axs_bool;
use NoOne4rever\Axessors\Types\axs_float;
use NoOne4rever\Axessors\Types\axs_int;

/**
 * Class TypeResolver.
 * 
 * Resolves Axessors types.
 * 
 * @package NoOne4rever\Axessors
 */
class TypeResolver
{
    private $type;
    
    public function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
     * Replaces internal PHP type with an Axessors type.
     *
     * @return string axessors type
     */
    public function replacePhpTypeWithAxsType(): string
    {
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
            return $this->type . '_ext';
        } else {
            return $this->type;
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
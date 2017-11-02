<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @package Axessors
 * @license GPL
 */

namespace Axessors;

/**
 * Class PropertyData.
 *
 * Stores information about Axessors property.
 */
class PropertyData
{
    /** @var \ReflectionProperty property's reflection */
    public $reflection;

    /** @var string[] access modifiers for getter and setter */
    private $accessibility;
    /** @var string an alias of property name */
    private $alias;
    /** @var array type tree */
    private $type;
    /** @var string[] conditions for setter */
    private $conditionsIn = [];
    /** @var string[] conditions for getter */
    private $conditionsOut = [];
    /** @var string[] handlers for setter */
    private $handlersIn = [];
    /** @var string[] handlers for getter */
    private $handlersOut = [];
    /** @var string[] methods' names */
    private $methods = [];

    /**
     * PropertyData constructor.
     * 
     * @param \ReflectionProperty $reflection property's information
     * @param array $tokens tokens form Axessors comment
     */
    public function __construct(\ReflectionProperty $reflection, array $tokens)
    {
        $parser = new Parser($reflection, $tokens);
        $this->reflection = $reflection;

        $this->accessibility = $parser->processAccessModifier();
        $this->type = $parser->processType();
        $this->conditionsIn = $parser->processInputConditions();
        $this->conditionsOut = $parser->processOutputConditions();
        $this->handlersIn = $parser->processInputHandlers();
        $this->handlersOut = $parser->processOutputHandlers();
        $this->alias = $parser->getAlias();
        $this->methods = $parser->processMethods();
    }

    /**
     * Getter for {@link PropertyData::$accessibility}.
     * 
     * @return string[] {@link PropertyData::$accessibility}
     */
    public function getAccessibility(): array
    {
        return $this->accessibility;
    }

    /**
     * Getter for {@link PropertyData::$alias}.
     * 
     * @return string {@link PropertyData::$alias}
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * Getter for {@link PropertyData::$methods}.
     * 
     * @return string[] {@link PropertyData::$methods}
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * Getter for {@link PropertyData::$conditionsIn}.
     * 
     * @return string[] {@link PropertyData::$conditionsIn}
     */
    public function getInputConditions(): array
    {
        return $this->conditionsIn;
    }

    /**
     * Getter for {@link PropertyData::$conditionsOut}.
     * 
     * @return string[] {@link PropertyData::$conditionsOut}
     */
    public function getOutputConditions(): array
    {
        return $this->conditionsOut;
    }

    /**
     * Getter for {@link PropertyData::$handlersOut}.
     * 
     * @return string[] {@link PropertyData::$handlersOut}
     */
    public function getOutputHandlers(): array
    {
        return $this->handlersOut;
    }

    /**
     * Getter for {@link PropertyData::$handlersIn}.
     * 
     * @return string[] {@link PropertyData::$handlersIn}
     */
    public function getInputHandlers(): array
    {
        return $this->handlersIn;
    }

    /**
     * Getter for {@link PropertyData::$type}.
     * 
     * @return array {@link PropertyData::$type}
     */
    public function getTypeTree(): array
    {
        return $this->type;
    }

    /**
     * Getter for {@link PropertyData::$alias}.
     * 
     * @return string {@link PropertyData::$alias}
     */
    public function getName(): string
    {
        return $this->alias;
    }
}

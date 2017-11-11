<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors;

/**
 * Class PropertyData.
 *
 * Stores information about Axessors property.
 *
 * @package NoOne4rever\Axessors
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
        
        $typeProcessor = new TypeProcessor($parser->getReflection(), $parser->getNamespace(), $parser->getTypeDef());
        $this->type = $typeProcessor->processType();
        $conditionsProcessor = new ConditionsProcessor($parser->getInConditions(), $parser->getOutConditions(), $parser->getNamespace());
        $this->conditionsIn = $conditionsProcessor->processInputConditions();
        $this->conditionsOut = $conditionsProcessor->processOutputConditions();
        $handlersProcessor = new HandlersProcessor($parser->getInHandlers(), $parser->getOutHandlers(), $parser->getNamespace());
        $this->handlersIn = $handlersProcessor->processInputHandlers();
        $this->handlersOut = $handlersProcessor->processOutputHandlers();
        $this->alias = $parser->getAlias();
        $this->methods = $parser->processMethods($typeProcessor->getTypeTree());
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

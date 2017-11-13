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
        $this->alias = $parser->getAlias();

        $typeProcessor = new TypeProcessor($parser->getReflection(), $parser->getNamespace(), $parser->getTypeDef());
        $typeProcessor->processType();
        $this->type = $typeProcessor->getTypeTree();
        
        $conditionsProcessor = new ConditionsProcessor($parser->getInConditions(), $parser->getOutConditions(),
            $parser->getNamespace());
        $this->conditionsIn = $conditionsProcessor->processInputData();
        $this->conditionsOut = $conditionsProcessor->processOutputData();
        
        $handlersProcessor = new HandlersProcessor($parser->getInHandlers(), $parser->getOutHandlers(),
            $parser->getNamespace());
        $this->handlersIn = $handlersProcessor->processInputData();
        $this->handlersOut = $handlersProcessor->processOutputData();
        
        $methodsProcessor = new MethodsProcessor($this->accessibility['write'] ?? '',
            $this->accessibility['read'] ?? '', $this->getAlias());
        $this->methods = $methodsProcessor->processMethods($typeProcessor->getTypeTree());
    }

    /**
     * Getter for {@see PropertyData::$accessibility}.
     *
     * @return string[] {@see PropertyData::$accessibility}
     */
    public function getAccessibility(): array
    {
        return $this->accessibility;
    }

    /**
     * Getter for {@see PropertyData::$alias}.
     *
     * @return string {@see PropertyData::$alias}
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * Getter for {@see PropertyData::$methods}.
     *
     * @return string[] {@see PropertyData::$methods}
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * Getter for {@see PropertyData::$conditionsIn}.
     *
     * @return string[] {@see PropertyData::$conditionsIn}
     */
    public function getInputConditions(): array
    {
        return $this->conditionsIn;
    }

    /**
     * Getter for {@see PropertyData::$conditionsOut}.
     *
     * @return string[] {@see PropertyData::$conditionsOut}
     */
    public function getOutputConditions(): array
    {
        return $this->conditionsOut;
    }

    /**
     * Getter for {@see PropertyData::$handlersOut}.
     *
     * @return string[] {@see PropertyData::$handlersOut}
     */
    public function getOutputHandlers(): array
    {
        return $this->handlersOut;
    }

    /**
     * Getter for {@see PropertyData::$handlersIn}.
     *
     * @return string[] {@see PropertyData::$handlersIn}
     */
    public function getInputHandlers(): array
    {
        return $this->handlersIn;
    }

    /**
     * Getter for {@see PropertyData::$type}.
     *
     * @return array {@see PropertyData::$type}
     */
    public function getTypeTree(): array
    {
        return $this->type;
    }

    /**
     * Getter for {@see PropertyData::$alias}.
     *
     * @return string {@see PropertyData::$alias}
     */
    public function getName(): string
    {
        return $this->alias;
    }
}

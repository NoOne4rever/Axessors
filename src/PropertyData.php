<?php

namespace Axessors;

class PropertyData
{
	public $reflection;

	private $accessibility;
	private $pseudonim;
	private $type;
	private $conditionsIn  = [];
	private $conditionsOut = [];
	private $handlersIn    = [];
	private $handlersOut   = [];
	private $methods       = [];

	public function __construct(\ReflectionProperty $reflection, array $tokens)
	{
		$parser = new Parser($reflection, $tokens);
		$this->reflection = $reflection;

		$this->accessibility = $parser->processAccessModifier();
		$this->type          = $parser->processType();
		$this->conditionsIn  = $parser->processInputConditions();
		$this->conditionsOut = $parser->processOutputConditions();
		$this->handlersIn    = $parser->processInputHandlers();
		$this->handlersOut   = $parser->processOutputHandlers();
		$this->pseudonim     = $parser->getAlias();
		$this->methods       = $parser->processMethods();
	}

	public function getAccessibility(): array
	{
		return $this->accessibility;
	}

	public function getPseudonim(): string
	{
		return $this->pseudonim;
	}

	public function getMethods(): array
	{
		return $this->methods;
	}

	public function getInputConditions(): array
	{
		return $this->conditionsIn;
	}

	public function getOutputConditions(): array
	{
		return $this->conditionsOut;
	}

	public function getOutputHandlers(): array
	{
		return $this->handlersOut;
	}

	public function getInputHandlers(): array
	{
		return $this->handlersIn;
	}

	public function getTypeTree(): array
	{
		return $this->type;
	}

	public function getName(): string
	{
		return $this->pseudonim;
	}
}

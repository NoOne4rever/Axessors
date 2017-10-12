<?php

namespace Axessors;

use Axessors\Exceptions\InternalError;
use Axessors\Exceptions\SyntaxError;
use Axessors\Exceptions\TypeError;

class Parser
{
	private const ACCESS_MODIFIER_1 = 1;
	private const KEYWORD_1         = 2;
	private const TYPE              = 3;
	private const CONDITIONS_1      = 4;
	private const HANDLERS_1        = 6;
	private const ACCESS_MODIFIER_2 = 7;
	private const KEYWORD_2         = 8;
	private const CONDITIONS_2      = 9;
	private const HANDLERS_2        = 11;
	private const PSEUDONIM         = 13;

	private const F_WRITABLE   = 'writable';
	private const S_WRITABLE   = 'wrt';
	private const F_ACCESSIBLE = 'accessible';
	private const S_ACCESSIBLE = 'axs';
	private const F_READABLE   = 'readable';
	private const S_READABLE   = 'rdb';
	private const A_PUBLIC     = '+';
	private const A_PROTECTED  = '~';
	private const A_PRIVATE    = '-';

	private $tokens;
	private $reflection;
	private $accessModifiers;
	private $pseudonim;
	private $namespace;
	private $readableFirst;
	private $typeTree = [];

	public function __construct(\ReflectionProperty $reflection, array $tokens)
	{
		$this->reflection    = $reflection;
		$this->tokens        = $tokens;
		$this->namespace     = $reflection->getDeclaringClass()->getNamespaceName();
		$this->readableFirst = preg_match('{^(rdb|readable)$}', $this->tokens[self::KEYWORD_1]);
		$this->validateStatements();
		$this->processPseudonim();
	}

	public function getPseudonim(): string
	{
		return $this->pseudonim;
	}

	public function processMethods(): array
	{
		$methods = [];
		$name = $this->pseudonim ?? $this->reflection->name;

		if (isset($this->accessModifiers['read']))
		{
			$methods[$this->accessModifiers['read']][] = 'get' . ucfirst($name);
		}
		if (isset($this->accessModifiers['write']))
		{
			$methods[$this->accessModifiers['write']][] = 'set' . ucfirst($name);
		}

		foreach ($this->typeTree as $index => $type)
		{
			$class = is_int($index) ? $type : $index;
			try
			{
				class_exists($class);
			}
			catch (InternalError $error)
			{
				continue;
			}
			foreach ((new \ReflectionClass($class))->getMethods() as $method)
			{
				$isAccessible = $method->isStatic() && $method->isPublic() && !$method->isAbstract();
				if ($isAccessible && preg_match('{^m_(in|out)_.*?PROPERTY.*}', $method->name))
				{
					if (substr($method->name, 0, 5) == 'm_in_' && isset($this->accessModifiers['write']))
					{
						$methods[$this->accessModifiers['write']][] = str_replace('PROPERTY', ucfirst($name), substr($method->name, 5));
					}
					elseif (substr($method->name, 0, 6) == 'm_out_' && isset($this->accessModifiers['read']))
					{
						$methods[$this->accessModifiers['read']][] = str_replace('PROPERTY', ucfirst($name), substr($method->name, 6));
					}
				}
			}
		}
		return $methods;
	}

	public function processInputHandlers(): array
	{
		return $this->processTokens(!$this->readableFirst, self::HANDLERS_1, self::HANDLERS_2, [$this, 'makeHandlersList']);
	}

	public function processOutputHandlers(): array
	{
		return $this->processTokens($this->readableFirst, self::HANDLERS_1, self::HANDLERS_2, [$this, 'makeHandlersList']);
	}

	public function processInputConditions(): array
	{
		return $this->processConditions(!$this->readableFirst);
	}

	public function processOutputConditions(): array
	{
		return $this->processConditions($this->readableFirst);
	}

	public function processAccessModifier(): array
	{
		$type = $this->getKeyword(self::KEYWORD_1);
		if ($type == 'access')
		{
			$this->accessModifiers = [
				'write' => $this->replaceSignWithWord($this->tokens[self::ACCESS_MODIFIER_1]),
				'read' => $this->replaceSignWithWord($this->tokens[self::ACCESS_MODIFIER_1])
			];
			return $this->accessModifiers;
		}
		$this->accessModifiers[$type] = $this->replaceSignWithWord($this->tokens[self::ACCESS_MODIFIER_1]);
		if (isset($this->tokens[self::KEYWORD_2]))
		{
			$type = $this->getKeyword(self::KEYWORD_2);
			$this->accessModifiers[$type] = $this->replaceSignWithWord($this->tokens[self::ACCESS_MODIFIER_2]);
		}
		return $this->accessModifiers;
	}

	public function processType(): array
	{
		$type = $this->getDefaultType();
		if (isset($this->tokens[self::TYPE]))
		{
			$this->typeTree = $this->makeTypeTree($this->tokens[self::TYPE]);
			if ($type != 'NULL')
			{
				foreach ($this->typeTree as $treeType => $subType)
				{
					if (is_int($treeType))
					{
						$treeType = $subType;
					}
					if ($type == $treeType || "{$type}_ext" == $treeType)
					{
						$this->validateTypeTree($this->typeTree);
						return $this->typeTree;
					}
				}
				throw new TypeError(
					"type in Axessors comment for {$this->reflection->getDeclaringClass()->name}::\${$this->reflection->name}
					does not equal defafult type of property"
				);
			}
		}
		else
		{
			if ($type == 'NULL')
			{
				var_dump($type);
				var_dump($this->tokens);
				throw new TypeError('type not defined');
			}
			else
			{
				$this->typeTree = [$type];
			}
		}
		$this->validateTypeTree($this->typeTree);
		return $this->typeTree;
	}

	private function getDefaultType(): string
	{
		if ($this->reflection->isStatic())
		{
			$this->reflection->setAccessible(true);
			$type = $this->replacePhpTypeWithAxsType(gettype($this->reflection->getValue()));
			$this->reflection->setAccessible(false);
		}
		else
		{
			$properties = $this->reflection->getDeclaringClass()->getDefaultProperties();
			$type = isset($properties[$this->reflection->name]) ? $this->replacePhpTypeWithAxsType($this->getType($properties[$this->reflection->name]))
				    : 'NULL';
		}
		return $type;
	}

	private function validateType(string $class): string
	{
		try
		{
			class_exists($class);
			return $class;
		}
		catch (InternalError $error)
		{
			$class = "$this->namespace\\$class";
			class_exists($class);
			return $class;
		}
	}

	private function validateTypeTree(array $tree): void
	{
		foreach ($tree as $type => $subtype)
		{
			if (!is_int($type))
			{
				//$type = $this->validateType($type);
				if (!is_subclass_of($type, 'Axessors\Types\Iterateable'))
				{
					throw new TypeError("\"$type\" is not iterateable {$this->reflection->getDeclaringClass()->name}::\${$this->reflection->name} Axessors comment");
				}
				//$subType = $this->validateTypeTree($subtype);
			}
		}
	}

	private function processPseudonim(): void
	{
		$this->pseudonim = $this->tokens[self::PSEUDONIM] ?? $this->reflection->name;
	}

	private function makeTypeTree(string $typeDefinition): array
	{
		$typeTree = [];
		$typeDefinition = explode('%', $this->replaceSensibleDelimiters($typeDefinition));
		foreach ($typeDefinition as $type)
		{
			if (($bracket = strpos($type, '[')) !== false)
			{
				$subtype         = substr($type, $bracket + 1, strlen($type) - $bracket - 2);
				$type            = substr($type, 0, $bracket);
				$type            = $this->validateType($this->replacePhpTypeWithAxsType($type));
				$typeTree[$type] = $this->makeTypeTree($subtype);
			}
			else
			{
				$type       = $this->validateType($this->replacePhpTypeWithAxsType($type));
				$typeTree[] = $type;
			}
		}
		return $typeTree;
	}

	private function replaceSignWithWord(string $sign): string
	{
		switch ($sign)
		{
			case self::A_PUBLIC:
				return 'public';
			case self::A_PROTECTED:
				return 'protected';
			case self::A_PRIVATE:
				return 'private';
		}
	}

	private function getType($var): string
	{
		if (is_callable($var))
		{
			return 'callable';
		}
		$type = gettype($var);
		return $type == 'integer' ? 'int' : $type;
	}

	private function replacePhpTypeWithAxsType(string $type): string
	{
		$_type = lcfirst($type);
		switch ($_type)
		{
			case 'int':
			case 'float':
			case 'bool':
			case 'string':
			case 'array':
			case 'object':
			case 'resource':
			case 'callable':
			case 'mixed':
				$type = "Axessors\\Types\\axs_{$_type}" . ($type !== $_type ? '_ext' : '');
		}
		return $type;
	}

	private function makeHandlersList(string $handlers): array
	{
		$result = preg_replace_callback(
			//'{`[^`]+`}',
			'{`([^`]|\\\\`)+((?<!\\\\)`)}',
			function (array $matches) {
				return addcslashes($matches[0], ',');
			},
			$handlers
		);
		$result = preg_split('{(?<!\\\\),\s*}', $result);
		foreach ($result as &$handler)
		{
			$handler = stripcslashes($handler);
		}
		return $result;
	}

	private function explodeConditions(string $conditions): array
	{
		$result = [];
		$conditions = preg_replace_callback(
			//'{`[^`]+`}',
			'{`([^`]|\\\\`)+((?<!\\\\)`)}',
			function (array $matches) {
				return addcslashes($matches[0], '&|');
			},
			$conditions
		);
		$conditions = preg_split('{\s*\|\|\s*}', $conditions);
		foreach ($conditions as $condition)
		{
			$result[] = preg_split('{\s*&&\s*}', $condition);
		}
		foreach ($result as $number => &$complexCondition)
		{
			if (is_array($complexCondition))
			{
				foreach ($complexCondition as $num => &$condition)
				{
					$condition = stripcslashes($condition);
				}
			}
			else
			{
				$complexCondition = stripcslashes($complexCondition);
			}
		}
		return $result;
	}

	private function processTokens(bool $mode, int $token1, int $token2, callable $callback): array
	{
		if ($mode && isset($this->tokens[$token1]))
		{
			return $callback($this->tokens[$token1]);
		}
		elseif (!$mode && isset($this->tokens[$token2]))
		{
			return $callback($this->tokens[$token2]);
		}
		else
		{
			return [];
		}
	}
	
	private function processConditions(bool $mode): array
	{
		return $this->processTokens($mode, self::CONDITIONS_1, self::CONDITIONS_2, [$this, 'makeConditionsTree']);
	}
	
	private function processHandlers(bool $mode): array
	{
		return $this->processTokens($mode, self::HANDLERS_1, self::HANDLERS_2, [$this, 'makeHandlersList']);
	}

	private function makeConditionsTree(string $conditions): array
	{
		$result     = [];
		$conditions = $this->explodeConditions($conditions);
		foreach ($conditions as $number => $condition)
		{
			foreach ($condition as $token)
			{
				if (count($condition) === 1)
				{
					$result[] = $token;
				}
				else
				{
					$result[$number][] = $token;
				}
			}
		}
		return $result;
	}

	private function replaceSensibleDelimiters(string $subject): string
	{
		$length   = strlen($subject);
		$brackets = 0;
		for ($i = 0; $i < $length; ++$i)
		{
			if ($subject{$i} == '[')
			{
				++$brackets;
			}
			elseif ($subject{$i} == ']')
			{
				--$brackets;
			}
			elseif ($subject{$i} == '|' && !$brackets)
			{
				$subject{$i} = '%';
			}
		}
		return $subject;
	}

	private function validateStatements(): void
	{
		if (isset($this->tokens[self::KEYWORD_2]))
		{
			if ($this->tokens[self::KEYWORD_1] == $this->tokens[self::KEYWORD_2])
			{
				throw new SyntaxError("the same statements in {$this->reflection->getDeclaringClass()->name}::\${$this->reflection->name} Axessors comment");
			}
			elseif (preg_match('{^(wrt|writable)$}', $this->tokens[self::KEYWORD_2]))
			{
				throw new SyntaxError(
					"\"writable\" statement must be the first in {$this->reflection->getDeclaringClass()->name}::\${$this->reflection->name} Axessors comment\""
				);
			}
		}
	}

	private function getKeyword(int $token): string
	{
		if (preg_match(sprintf('{^(%s|%s)$}', self::F_ACCESSIBLE, self::S_ACCESSIBLE), $this->tokens[$token]))
		{
			return 'access';
		}
		elseif (preg_match(sprintf('{^(%s|%s)$}', self::F_WRITABLE, self::S_WRITABLE), $this->tokens[$token]))
		{
			return 'write';
		}
		elseif (preg_match(sprintf('{^(%s|%s)$}', self::F_READABLE, self::S_READABLE), $this->tokens[$token]))
		{
			return 'read';
		}
	}
}

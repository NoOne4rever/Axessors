<?php

namespace Axessors;

use Axessors\Exceptions\ParseError;

abstract class Lexer
{
	protected $reflection;
	protected $source;
	protected $startLine;
	protected $endLine;
	protected $currentLine;
	protected $currentSym = 0;

	private $lineNumber = 0;

	public function __construct(\ReflectionClass $reflection)
	{
		$this->reflection = $reflection;
		$this->openSource();
		$this->skipFirstLines();
	}

	protected function parse(string $code, array $expectations, array $requiredItems): array
	{
		$this->currentSym  = 0;
		$code = trim($code);
		$result            = [];
		foreach ($expectations as $index => $pattern)
		{
			$this->skipWhitespace($code);
			preg_match($pattern, substr($code , $this->currentSym), $token);
			if (empty($token))
			{
				if (in_array($index, $requiredItems))
				{
					throw new ParseError("token with pattern \"$pattern\" not found while parsing {$this->reflection->getFileName()}:{$this->lineNumber}");
				}
			}
			else
			{
				$result[$index]    = $token[0];
				$this->currentSym += strlen($token[0]);
			}
		}
		return $result;
	}

	private function skipWhitespace(string $code): void
	{
		preg_match('{^\s+}', substr($code, $this->currentSym), $whitespace);
		if (!empty($whitespace))
		{
			$this->currentSym += strlen($whitespace[0]);
		}
	}

	protected function openSource(): void
	{
		$this->source    = fopen($this->reflection->getFileName(), 'rb');
		$this->startLine = $this->reflection->getStartLine();
		$this->endLine   = $this->reflection->getEndLine();
	}

	protected function readLine(): void
	{
		$this->currentLine = fgets($this->source);
		++$this->lineNumber;
	}

	protected function isLineEmpty(): bool
	{
		return $this->currentLine !== false;
	}

	protected function skipFirstLines(): void
	{
		for ($i = 1; $i < $this->startLine; ++$i)
		{
			$this->readLine();
		}
	}
}

<?php

namespace Axessors;

class CommentLexer extends Lexer
{
	private const AXS_COMMENT      = '{^#>}';
	private const ACCESS_MODIFIER  = '{^(\+|~|-)}';
	private const KEYWORD          = '{^((accessi|(writ|read)a)ble|axs|wrt|rdb)}';
	private const TYPE             = '{^((((\\\\)?[a-zA-Z_][a-zA-Z\d_]*(\\\\[a-zA-Z_][a-zA-Z\d_]*)*)(\[(?1)\])?)(\|(?2))*)}';
	//private const CONDITIONS       = '{^((?(1)\s*(&&|\|\|)\s*)(\d+(,\d+)?\.\.\d+(,\d+)?|((<|>|!|=)=|%|<|>)\s+\d+(,\d+)?|`[^`]+`))+}';
	//private const HANDLERS         = '{^((?(1),\s*)([a-zA-Z_][a-zA-Z0-9_]*|`[^`]+`))+}';
	private const HANDLERS         = '{^((?(1),\s*)([a-zA-Z_][a-zA-Z0-9_]*|`([^`]|\\\\`)+((?<!\\\\)`)))+}';
	private const CONDITIONS       = '{^((?(1)\s*(&&|\|\|)\s*)(\d+(,\d+)?\.\.\d+(,\d+)?|((<|>|!|=)=|%|<|>)\s+\d+(,\d+)?|`([^`]|\\\\`)+((?<!\\\\)`)))+}';
	private const COMMA            = '{^,}';
	private const HANDLERS_SIGN    = '{^>>}';
	private const PSEUDONIM_SIGN   = '{^=>}';
	private const PSEUDONIM        = '{^[a-zA-Z_][a-zA-Z0-9_]*}';

	private const TOKEN_LIST       = [
		self::AXS_COMMENT,
		self::ACCESS_MODIFIER,
		self::KEYWORD,
		self::TYPE,
		self::CONDITIONS,
		self::HANDLERS_SIGN,
		self::HANDLERS,
		self::ACCESS_MODIFIER,
		self::KEYWORD,
		self::CONDITIONS,
		self::HANDLERS_SIGN,
		self::HANDLERS,
		self::PSEUDONIM_SIGN,
		self::PSEUDONIM
	];
	private const REQUIRED_TOKENS  = [0, 1, 2];

	private const AXS_COMMENT_TOKEN = '#>';

	public function getClassData(): ClassData
	{
		$classData = new ClassData($this->reflection);
		for ($i = $this->startLine; $this->isLineEmpty() && $i <= $this->endLine; ++$i)
		{
			$this->readLine();
			if (!$this->isAxsPropertyDef())
			{
				continue;
			}
			//$code = addcslashes($this->getAxsComment(), '\\');
			$code = preg_replace_callback(
				'/`([^`]|\\\\`)+((?<!\\\\)`)/',
				function(array $matches): string
				{
					return addcslashes($matches[0], '\\');
				},
				$this->getAxsComment()
			);
			$propertyData = new PropertyData(
				$this->reflection->getProperty($this->getPropertyName()),
				$this->parse(
					$code,
					self::TOKEN_LIST,
					self::REQUIRED_TOKENS
				)
			);
			$classData->addProperty($this->getPropertyName(), $propertyData);
		}
		return $classData;
	}

	private function getAxsComment(): string
	{
		return substr($this->currentLine, strpos($this->currentLine, self::AXS_COMMENT_TOKEN));
	}

	private function getPropertyName(): string
	{
		preg_match('{\$[a-zA-Z_][a-zA-Z0-9_]*}', $this->currentLine, $property);
		return substr($property[0], 1);
	}

	private function isAxsPropertyDef(): bool
	{
		return preg_match('{^\s*(public|private|protected)\s+(static\s+)?\$[a-zA-Z_][a-zA-Z0-9_]*.*?;\s+#>}', $this->currentLine);
	}
}

<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors;

/**
 * Class CommentLexer.
 *
 * Splits Axessors comment into array of possible tokens.
 *
 * @package NoOne4rever\Axessors
 */
class CommentLexer extends Lexer
{
    private const AXS_COMMENT = '/^#:/';
    private const ACCESS_MODIFIER = '/^(\+|~|-)/';
    private const KEYWORD = '/^(accessible|writable|readable|axs|wrt|rdb)/';
    private const TYPE = '/^((((\\\\)?[a-zA-Z_][a-zA-Z\d_]*(\\\\[a-zA-Z_][a-zA-Z\d_]*)*)(\[(?1)\])?)(\|(?2))*)/';
    private const HANDLERS = '/^((?(1),\s*)([a-zA-Z_][a-zA-Z0-9_]*|`([^`]|\\\\`)+((?<!\\\\)`)))+/';
    private const CONDITIONS = '/^((?(1)\s*(&&|\|\|)\s*)(\d+(,\d+)?\.\.\d+(,\d+)?|((<|>|!|=)=|%|<|>)\s+\d+(,\d+)?|`([^`]|\\\\`)+((?<!\\\\)`)))+/';
    private const HANDLERS_SIGN = '/^->/';
    private const ALIAS_SIGN = '/^=>/';
    private const ALIAS = '/^[a-zA-Z_][a-zA-Z0-9_]*/';

    private const TOKEN_LIST = [
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
        self::ALIAS_SIGN,
        self::ALIAS
    ];
    private const REQUIRED_TOKENS = [0, 1, 2];

    private const AXS_COMMENT_TOKEN = '#:';

    /**
     * Returns class' data with Axessors properties.
     *
     * @return ClassData class' data
     */
    public function getClassData(): ClassData
    {
        $classData = new ClassData($this->reflection);
        for ($i = $this->startLine; $this->isLineEmpty() && $i <= $this->endLine; ++$i) {
            $this->readLine();
            if (!$this->isAxsPropertyDef()) {
                continue;
            }
            $injProcessor = new InjectedStringSuit($this->getAxsComment());
            $code = $injProcessor->addSlashes('\\')->get();
            $properties = $this->getProperties();
            $tokenList = $this->getTokenList(count($properties));
            $tokens = $this->parse($code, $tokenList, self::REQUIRED_TOKENS);
            foreach ($this->getProperties() as $propertyName) {
                $propertyData = new PropertyData($this->reflection->getProperty($propertyName), $tokens);
                $classData->addProperty($propertyName, $propertyData);
            }
        }
        return $classData;
    }

    /**
     * Returns dynamically created array of token signatures.
     *
     * @param int $properties number of properties 
     * 
     * @return array tokens
     */
    private function getTokenList(int $properties): array 
    {
        if ($properties == 1) {
            return self::TOKEN_LIST;
        } else {
            return array_slice(self::TOKEN_LIST, 0, count(self::TOKEN_LIST) - 2);
        }
    }

    /**
     * Extracts Axessors comment from the code.
     *
     * @return string Axessors comment
     */
    private function getAxsComment(): string
    {
        return substr($this->currentLine, strpos($this->currentLine, self::AXS_COMMENT_TOKEN));
    }

    /**
     * Extracts property name from the code.
     *
     * @return string[] property name
     */
    private function getProperties(): array
    {
        $line = substr($this->currentLine, 0, strpos($this->currentLine, self::AXS_COMMENT_TOKEN));
        preg_match_all('/(?<=\$)[a-z_][a-z\d_]*/i', $line, $properties);
        return $properties[0];
    }

    /**
     * Checks if the code given is Axessors property definition.
     *
     * @return bool result of the checkout
     */
    private function isAxsPropertyDef(): bool
    {
        return (bool)preg_match('/\s*(public|protected|private)(\s*static)?\s+((?(3),)\s*\$[a-z_][a-z\d_]*)+.*?;\s*#:.+/i',
            $this->currentLine);
    }
}

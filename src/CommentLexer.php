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
     * @return string property name
     */
    private function getPropertyName(): string
    {
        preg_match('{\$[a-zA-Z_][a-zA-Z0-9_]*}', $this->currentLine, $property);
        return substr($property[0], 1);
    }

    /**
     * Checks if the code given is Axessors property definition. 
     * 
     * @return bool result of the checkout
     */
    private function isAxsPropertyDef(): bool
    {
        return (bool)preg_match('{^\s*(public|private|protected)\s+(static\s+)?\$[a-zA-Z_][a-zA-Z0-9_]*.*?;\s+#:}',
            $this->currentLine);
    }
}

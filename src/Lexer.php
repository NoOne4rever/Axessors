<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors;

use NoOne4rever\Axessors\Exceptions\ParseError;

/**
 * Class Lexer.
 *
 * A general implementation of Axessors tokenizer.
 *
 * @package NoOne4rever\Axessors
 */
abstract class Lexer
{
    /** @var \ReflectionClass class' reflection */
    protected $reflection;
    /** @var resource file with PHP-code */
    protected $source;
    /** @var int the first line of class declaration */
    protected $startLine;
    /** @var int the last line of class declaration */
    protected $endLine;
    /** @var string current line */
    protected $currentLine;
    /** @var int number of the current symbol */
    protected $currentSym = 0;

    /** @var int number of the current line */
    private $lineNumber = 0;

    /**
     * Lexer constructor.
     *
     * @param \ReflectionClass $reflection class' reflection
     */
    public function __construct(\ReflectionClass $reflection)
    {
        $this->reflection = $reflection;
        $this->openSource();
        $this->skipFirstLines();
    }

    /**
     * Splits given code into array of tokens.
     *
     * @param string $code
     * @param string[] $expectations tokens' patterns
     * @param int[] $requiredItems necessary tokens
     * @return string[] found tokens
     * @throws ParseError if an important token not found in Axessors comment
     */
    protected function parse(string $code, array $expectations, array $requiredItems): array
    {
        $this->currentSym = 0;
        $code = trim($code);
        $result = [];
        foreach ($expectations as $index => $pattern) {
            $this->skipWhitespace($code);
            preg_match($pattern, substr($code, $this->currentSym), $token);
            if (empty($token)) {
                if (in_array($index, $requiredItems)) {
                    throw new ParseError("token with pattern \"$pattern\" not found while parsing {$this->reflection->getFileName()}:{$this->lineNumber}");
                }
            } else {
                $result[$index] = $token[0];
                $this->currentSym += strlen($token[0]);
            }
        }
        return $result;
    }

    /**
     * Skips whitespace symbols in code.
     *
     * @param string $code line of code
     */
    private function skipWhitespace(string $code): void
    {
        preg_match('{^\s+}', substr($code, $this->currentSym), $whitespace);
        if (!empty($whitespace)) {
            $this->currentSym += strlen($whitespace[0]);
        }
    }

    /**
     * Opens source with class declaration.
     */
    protected function openSource(): void
    {
        $this->source = fopen($this->reflection->getFileName(), 'rb');
        $this->startLine = $this->reflection->getStartLine();
        $this->endLine = $this->reflection->getEndLine();
    }

    /**
     * Reads a line from source.
     */
    protected function readLine(): void
    {
        $this->currentLine = fgets($this->source);
        ++$this->lineNumber;
    }

    /**
     * Checks if the line is empty.
     *
     * @return bool result of the checkout
     */
    protected function isLineEmpty(): bool
    {
        return $this->currentLine !== false;
    }

    /**
     * Skips lines before class declaration.
     */
    protected function skipFirstLines(): void
    {
        for ($i = 1; $i < $this->startLine; ++$i) {
            $this->readLine();
        }
    }
}

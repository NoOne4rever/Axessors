<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors;

/**
 * Class InjectedStringParser.
 * 
 * Processes *injected* callbacks and conditions.
 * 
 * @package NoOne4rever\Axessors
 */
class InjectedStringSuit
{
    /** @var string expression */
    private $expression;

    /**
     * InjectedStringSuit constructor.
     *
     * @param string $expression expression
     */
    public function __construct(string $expression)
    {
        $this->expression = $expression;
    }

    /**
     * Processes "$." in injected string.
     * 
     * @return InjectedStringSuit object for methods chain
     */
    public function processThis(): self
    {
        $expression = preg_replace_callback('/"[^"]"|\'[^\']\'/', function (array $matches) {
            return str_replace('$.', '\\$.', $matches[0]);
        }, $this->expression);
        $expression = preg_replace('/\$\./', '$this->', $expression);
        $expression = preg_replace_callback('/"[^"]"|\'[^\']\'/', function (array $matches) {
            return str_replace('\\$.', '$.', $matches[0]);
        }, $expression);
        $this->expression = $expression;
        return $this;
    }

    /**
     * Resolves class names in *injected* callbacks and conditions.
     *
     * @param string $namespace namespace
     * 
     * @return InjectedStringSuit object for methods chain
     */
    public function resolveClassNames(string $namespace): self
    {
        $expression = preg_replace_callback('/"[^"]"|\'[^\']\'/', function (array $matches) {
            return str_replace(':', ':\\', $matches[0]);
        }, $this->expression);
        $expression = preg_replace('/(?<!:):(?=([a-zA-Z_][a-zA-Z0-9_]*))/', "$namespace\\", $expression);
        $expression = preg_replace_callback('/"[^"]"|\'[^\']\'/', function (array $matches) {
            return str_replace(':\\', ':', $matches[0]);
        }, $expression);
        $this->expression = $expression;
        return $this;
    }

    /**
     * Adds slashes to string.
     *
     * @param string $charlist symbols add slashes to
     * 
     * @return InjectedStringSuit object for methods chain 
     */
    public function addSlashes(string $charlist): self
    {
        $this->expression = preg_replace_callback(
            '/`([^`]|\\\\`)+((?<!\\\\)`)/',
            function (array $matches) use($charlist): string {
                return addcslashes($matches[0], $charlist);
            },
            $this->expression
        );
        return $this;
    }
    
    /**
     * Getter for InjectedStringProcessor::$expression.
     * 
     * @return string processed expression
     */
    public function get(): string 
    {
        return $this->expression;
    }
}
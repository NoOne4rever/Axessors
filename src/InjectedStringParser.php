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
class InjectedStringParser
{
    private $expression;
    
    public function __construct(string $expression)
    {
        $this->expression = $expression;
    }

    /**
     * Resolves class names in *injected* callbacks and conditions.
     *
     * @param string $namespace namespace
     * @return string expression with resolved class names
     */
    public function resolveClassNames(string $namespace): string
    {
        $expression = preg_replace_callback('/"[^"]"|\'[^\']\'/', function (array $matches) {
            return str_replace(':', ':\\', $matches[0]);
        }, $this->expression);
        $expression = preg_replace('/(?<!:):(?=([a-zA-Z_][a-zA-Z0-9_]*))/', "$namespace\\", $expression);
        $expression = preg_replace_callback('/"[^"]"|\'[^\']\'/', function (array $matches) {
            return str_replace(':\\', ':', $matches[0]);
        }, $expression);
        return $expression;
    }
}
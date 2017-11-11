<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors;

/**
 * Class HandlersProcessor.
 * 
 * Processes Axessors handlers.
 * 
 * @package NoOne4rever\Axessors
 */
class HandlersProcessor
{
    private $inputHandlers;
    private $outputHandlers;
    private $namespace;
    
    public function __construct(string $in, string $out, string $namespace)
    {
        $this->inputHandlers = $in;
        $this->outputHandlers = $out;
        $this->namespace = $namespace;
    }

    /**
     * Creates list of handlers from a string of handlers definition.
     *
     * @param string $handlers handlers
     * @return string[] handlers
     */
    private function makeHandlersList(string $handlers): array
    {
        $result = preg_replace_callback(
            '{`([^`]|\\\\`)+((?<!\\\\)`)}',
            function (array $matches) {
                return addcslashes($matches[0], ',');
            },
            $handlers
        );
        $result = preg_split('{(?<!\\\\),\s*}', $result);
        foreach ($result as &$handler) {
            $injProcessor = new InjectedStringParser(stripcslashes($handler));
            $handler = $injProcessor->resolveClassNames($this->namespace);
        }
        return $result;
    }

    /**
     * Creates list of handlers for input data.
     *
     * @return string[] handlers
     */
    public function processInputHandlers(): array
    {
        return $this->inputHandlers === '' ? [] : $this->makeHandlersList($this->inputHandlers);
    }

    /**
     * Creates list of handlers for output data.
     *
     * @return string[] handlers
     */
    public function processOutputHandlers(): array
    {
        return $this->outputHandlers === '' ? [] : $this->makeHandlersList($this->outputHandlers);
    }
}
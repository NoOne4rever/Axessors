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
    /** @var string handlers for setter */
    private $inputHandlers;
    /** @var string handlers for getter */
    private $outputHandlers;
    /** @var string class namespace */
    private $namespace;

    /**
     * HandlersProcessor constructor.
     * 
     * @param string $in input handlers
     * @param string $out output handlers
     * @param string $namespace class namespace
     */
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
        $injProcessor = new InjectedStringParser($handlers);
        $result = $injProcessor->addSlashes(',');
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
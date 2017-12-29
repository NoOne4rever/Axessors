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
class HandlersProcessor extends TokenProcessor
{
    /**
     * Creates list of handlers from a string of handlers definition.
     *
     * @param string $handlers handlers
     * @return string[] handlers
     */
    private function makeHandlersList(string $handlers): array
    {
        if ($handlers === '') {
            return [];
        }
        $injProcessor = new InjectedStringSuit($handlers);
        $result = $injProcessor->addSlashes(',')->get();
        $result = preg_split('{(?<!\\\\),\s*}', $result);
        foreach ($result as &$handler) {
            $injProcessor = new InjectedStringSuit(stripcslashes($handler));
            $handler = $injProcessor->resolveClassNames($this->namespace)->processThis()->wrapWithClosure()->get();
        }
        return $result;
    }

    /**
     * Creates list of handlers for input data.
     *
     * @return string[] handlers
     */
    public function processInputData(): array
    {
        return $this->makeHandlersList($this->input);
    }

    /**
     * Creates list of handlers for output data.
     *
     * @return string[] handlers
     */
    public function processOutputData(): array
    {
        return $this->makeHandlersList($this->output);
    }
}
<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors;

use NoOne4rever\Axessors\Exceptions\OopError;

/**
 * Class HandlersSuit.
 * 
 * Processes Axessors handlers.
 * 
 * @package NoOne4rever\Axessors
 */
class HandlersSuit extends RunningSuit
{
    /**
     * Executes handlers defined in the Axessors comment.
     *
     * @param $value mixed value of the property
     * @return mixed new value of the property
     * @throws OopError if the property does not have one of the handlers defined in the Axessors comment
     */
    public function executeHandlers($value)
    {
        $handlers = $this->mode == RunningSuit::OUTPUT_MODE ? $this->propertyData->getOutputHandlers() : $this->propertyData->getInputHandlers();
        foreach ($handlers as $handler) {
            if (strpos($handler, '`') !== false) {
                $value = $this->runInjectedHandler($handler, $value);
            } else {
                $value = $this->runStandardHandler($handler, $value);
            }
        }
        return $value;
    }

    /**
     * Runs *injected* handler.
     * 
     * @param string $handler *injected* handler
     * @param mixed $value a value to process
     * @return mixed the result of handler execution
     */
    private function runInjectedHandler(string $handler, $value)
    {
        $handler = str_replace('\\`', '`', substr($handler, 1, strlen($handler) - 2));
        if (is_null($this->object)) {
            return call_user_func("{$this->class}::__axessorsExecute", $handler, $value, false);
        } else {
            return $this->object->__axessorsExecute($handler, $value, false);
        }        
    }

    /**
     * Runs Axessors handler.
     * 
     * @param string $handler handler name
     * @param mixed $value the value to process
     * @return mixed the result of handler execution 
     * @throws OopError if the called handler not found
     */
    private function runStandardHandler(string $handler, $value)
    {
        foreach ($this->propertyData->getTypeTree() as $type => $subType) {
            $reflection = new \ReflectionClass('\Axessors\Types\\' . is_int($type) ? $subType : $type);
            foreach ($reflection->getMethods() as $method) {
                $isAccessible = $method->isPublic() && $method->isStatic() && !$method->isAbstract();
                $isThat = call_user_func([$reflection->name, 'is'], $value);
                if ($isAccessible && $isThat && "h_$handler" == $method->name) {
                    return call_user_func([$reflection->name, $method->name], $value, false);
                }
            }
        }
        throw new OopError("property {$this->class}::\${$this->propertyData->getName()} does not have handler \"$handler\"");
    }
}
<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors;

use NoOne4rever\Axessors\Exceptions\TypeError;

/**
 * Class HandlersSuit.
 * 
 * Processes Axessors handlers.
 * 
 * @package NoOne4rever\Axessors
 */
class HandlersRunner extends RunningSuit
{
    /**
     * Executes handlers defined in the Axessors comment.
     *
     * @param $value mixed value of the property
     * @return mixed new value of the property
     */
    public function executeHandlers($value)
    {
        $handlers = $this->mode == RunningSuit::OUTPUT_MODE ? $this->propertyData->getOutputHandlers() : $this->propertyData->getInputHandlers();
        foreach ($handlers as $handler) {
            if (strpos($handler, '`') !== false) {
                $value = $this->executeInjectedString($handler, $value, false);
            } else {
                $value = $this->runStandardHandler($handler, $value);
            }
        }
        return $value;
    }

    /**
     * Runs Axessors handler.
     * 
     * @param string $handler handler name
     * @param mixed $value the value to process
     * @return mixed the result of handler execution
     * @throws TypeError if the called handler not found
     */
    private function runStandardHandler(string $handler, $value)
    {
        foreach ($this->propertyData->getTypeTree() as $type => $subType) {
            $reflection = new \ReflectionClass('\Axessors\Types\\' . is_int($type) ? $subType : $type);
            foreach ($reflection->getMethods() as $method) {
                if ($this->isCalledHandler($method, $handler, $reflection->name, $value)) {
                    return call_user_func([$reflection->name, $method->name], $value, false);
                }
            }
        }
        throw new TypeError("property {$this->class}::\${$this->propertyData->getName()} does not have handler \"$handler\"");
    }

    /**
     * Checks if the method can be called.
     * 
     * @param \ReflectionMethod $method method reflection
     * @return bool the result of the checkout
     */
    private function isMethodAccessible(\ReflectionMethod $method): bool
    {
        return $method->isPublic() && $method->isStatic() && !$method->isAbstract();
    }

    /**
     * Checks if the value match handler's type.
     * 
     * @param string $type type
     * @param mixed $value value
     * @return bool the result of the checkout
     */
    private function valueMatchType(string $type, $value): bool
    {
        return call_user_func([$type, 'is'], $value);
    }

    /**
     * Checks if the handler can be called.
     * 
     * @param \ReflectionMethod $method method reflection
     * @param string $handler handler name
     * @param string $type type
     * @param mixed $value value
     * @return bool the result of the checkout
     */
    private function isCalledHandler(\ReflectionMethod $method, string $handler, string $type, $value): bool 
    {
        return $this->isMethodAccessible($method) && $this->valueMatchType($type, $value) && "h_$handler" === $method->name;
    }
}
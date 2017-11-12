<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors;

use NoOne4rever\Axessors\Exceptions\ReThrownError;

/**
 * Trait Axessors.
 *
 * This trait is used to indicate that a class uses the library and provides them with method call handlers.
 * If you wouldn't use method handling functionality, you can add trait {@see Axessors\Axs} to your class.
 *
 * @package NoOne4rever\Axessors
 */
trait Axessors
{
    /**
     * Redirects an instance method call.
     *
     * @param string $method the name of the called method
     * @param array $args arguments of the called method
     * @return mixed result of the called method
     * @throws ReThrownError is thrown when there is an error in *injected* callback or condition code
     */
    public function __call(string $method, array $args)
    {
        return self::__axessorsCall($method, $args, $this);
    }

    /**
     * Redirects a static method call.
     *
     * @param string $method the name of the called method
     * @param array $args arguments of the called method
     * @return mixed result of the called method
     * @throws ReThrownError is thrown when there is an error in *injected* callback or condition code
     */
    public static function __callStatic(string $method, array $args)
    {
        return self::__axessorsCall($method, $args);
    }

    /**
     * Executes *injected* callback or condition.
     *
     * @param string $code code to execute
     * @param mixed $_var value to process
     * @param bool $mode mode of execution
     * @return mixed the result or condition or callback
     * @throws ReThrownError if an error occurred while evaluating *injected* callback or condition
     */
    public function __axessorsExecute(string $code, $_var, bool $mode)
    {
        $var = $_var;
        try {
            $result = eval('return ' . $code . ';'); // evaluation of the code written in Axessors comment
        } catch (\Throwable $error) {
            throw new ReThrownError("an error occurred while evaluating executable string \"$code\": {$error->getMessage()}");
        }
        if ($var != $_var) {
            return $var;
        } else {
            return $mode ? $result : $var;
        }
    }

    /**
     * Redirects Axessors method call.
     *
     * @param string $method method name
     * @param array $args method arguments
     * @param object|null $object object for instance method call
     * @return mixed the result of called method
     */
    private static function __axessorsCall(string $method, array $args, $object = null)
    {
        if (method_exists(static::class, $method)) {
            return call_user_func_array([$object ?? static::class, $method], $args);
        } else {
            $callProcessor = new CallProcessor(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), $object);
            return $callProcessor->call($args, $method);
        }
    }
}

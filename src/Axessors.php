<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors;

use NoOne4rever\Axessors\Exceptions\ReThrownError;

require_once __DIR__ . '\Axs.php';

/**
 * Trait Axessors.
 *
 * This trait is used to indicate that a class uses the library and provides them with method call handlers.
 * If you wouldn't use method handling functionality, you can add trait {@link Axessors\Axs}.
 *
 * @package NoOne4rever\Axessors
 */
trait Axessors
{
    /**
     * Redirects an instance method call.
     *
     * @param string $method the name of the called method
     * @param array $args the arguments of the called method
     * @return mixed return value of the called method
     * @throws ReThrownError is thrown when there is an error in the eval()'d code
     */
    public function __call(string $method, array $args)
    {
        return self::__axessorsCall($method, $args, $this);
    }

    /**
     * Redirects a static method call.
     *
     * @param string $method the name of the called method
     * @param array $args the arguments of the called method
     * @return mixed return value of the called method
     * @throws ReThrownError is thrown when there is an error in the eval()'d code
     */
    public static function __callStatic(string $method, array $args)
    {
        return self::__axessorsCall($method, $args);
    }
    
    private static function __axessorsCall(string $method, array $args, $object = null)
    {
        if (method_exists(static::class, $method)) {
            return call_user_func_array([static::class, $method], $args);
        } else {
            $callProcessor = new CallProcessor(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2), $object);
            return $callProcessor->call($args, $method);
        }
    }
    
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
}

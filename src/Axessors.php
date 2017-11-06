<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @package Axessors
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
        if (method_exists(static::class, $method)) {
            return call_user_func_array([$this, $method], $args);
        } elseif ($method == '__axessors_execute_instance') {
            list($code, $_var, $mode) = $args;
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
        } else {
            $callProcessor = new CallProcessor(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2), $this);
            return $callProcessor->call($args, $method);
        }
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
        if (method_exists(static::class, $method)) {
            return call_user_func_array([static::class, $method], $args);
        } elseif ($method == '__axessors_execute_static') {
            list($code, $var) = $args;
            try {
                $result = eval('return ' . $code . ';'); // evaluation of the code written in Axessors comment
            } catch (\Throwable $error) {
                $class = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[1]['class'];
                throw new ReThrownError("an error occurred while evaluating executable string in class $class: " . $error->getMessage());
            }
            if (preg_match('{^\$var\s*(\+|-|/|\*|%)?=\s*.+$}', $code)) {
                return $result;
            } else {
                return $var;
            }
        } else {
            $callProcessor = new CallProcessor(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2));
            return $callProcessor->call($args, $method);
        }
    }
}

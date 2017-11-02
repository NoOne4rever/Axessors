<?php

namespace Axessors;

use Axessors\Exceptions\ReThrownError;

require_once __DIR__ . '\Axs.php';

trait Axessors
{
    public function __call(string $method, array $args)
    {
        if (method_exists(static::class, $method)) {
            return call_user_func_array([$this, $method], $args);
        } elseif ($method == '__axessors_execute_instance') {
            list($code, $_var, $mode) = $args;
            $var = $_var;
            try {
                $result = eval('return ' . $code . ';');
            } catch (\ParseError $error) {
                throw new ReThrownError("an error occurred while evaluating executable string \"$code\": " . $error->getMessage());
            }
            /*			if (preg_match('{^\$var\s*(\+|-|/|\*|%)?=\s*.+$}', $code))
                        {
                            return $result;
                        }
                        else
                        {
                            return $var;
                        }*/
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

    public static function __callStatic(string $method, array $args)
    {
        if (method_exists(static::class, $method)) {
            return call_user_func_array([static::class, $method], $args);
        } elseif ($method == '__axessors_execute_static') {
            list($code, $var) = $args;
            try {
                $result = eval('return ' . $code . ';');
            } catch (\ParseError $error) {
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

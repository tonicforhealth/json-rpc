<?php

namespace Tonic\Component\Reflection;

/**
 * Static factory for reflection function.
 */
class ReflectionFunctionFactory
{
    /**
     * @param callable $callable
     *
     * @return \ReflectionFunctionAbstract
     */
    public static function createFromCallable(callable $callable)
    {
        return is_array($callable) && (count($callable) == 2)
            ? new \ReflectionMethod($callable[0], $callable[1])
            : new \ReflectionFunction($callable);
    }
}

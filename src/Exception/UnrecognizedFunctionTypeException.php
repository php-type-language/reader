<?php

declare(strict_types=1);

namespace TypeLang\Reader\Exception;

/**
 * Occurs when the return type of a function or of a method cannot be
 * recognized.
 */
final class UnrecognizedFunctionTypeException extends UnrecognizedReflectionTypeException
{
    public static function becauseFunctionTypeIsUnrecognized(
        \ReflectionType $type,
        \ReflectionFunctionAbstract $function,
        ?\Throwable $previous = null,
    ): self {
        return new self(
            type: $type,
            reflector: $function,
            message: \sprintf(
                'Reflection type "%s" of function "%s" is not supported',
                \addcslashes($type::class, '"'),
                \addcslashes(self::formatFunction($function) . '()', '"'),
            ),
            previous: $previous,
        );
    }
}

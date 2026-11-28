<?php

declare(strict_types=1);

namespace TypeLang\Reader\Exception;

/**
 * Occurs when the type of a parameter of a function or of a method cannot be
 * recognized.
 */
final class UnrecognizedParameterTypeException extends UnrecognizedReflectionTypeException
{
    public static function becauseParameterTypeIsUnrecognized(
        \ReflectionType $type,
        \ReflectionParameter $parameter,
        ?\Throwable $previous = null,
    ): self {
        $function = self::formatFunction($parameter->getDeclaringFunction());

        return new self(
            type: $type,
            reflector: $parameter,
            message: \sprintf(
                'Reflection type "%s" of parameter "%s" is not supported',
                \addcslashes($type::class, '"'),
                \addcslashes($function . '($' . $parameter->name . ')', '"'),
            ),
            previous: $previous,
        );
    }
}

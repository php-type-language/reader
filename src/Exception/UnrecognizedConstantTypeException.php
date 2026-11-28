<?php

declare(strict_types=1);

namespace TypeLang\Reader\Exception;

/**
 * Occurs when the type of a class constant cannot be recognized.
 */
final class UnrecognizedConstantTypeException extends UnrecognizedReflectionTypeException
{
    public static function becauseConstantTypeIsUnrecognized(
        \ReflectionType $type,
        \ReflectionClassConstant $constant,
        ?\Throwable $previous = null,
    ): self {
        return new self(
            type: $type,
            reflector: $constant,
            message: \sprintf(
                'Reflection type "%s" of constant "%s" is not supported',
                \addcslashes($type::class, '"'),
                \addcslashes($constant->class . '::' . $constant->name, '"'),
            ),
            previous: $previous,
        );
    }
}

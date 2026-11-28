<?php

declare(strict_types=1);

namespace TypeLang\Reader\Exception;

/**
 * Occurs when the type of a property cannot be recognized.
 */
final class UnrecognizedPropertyTypeException extends UnrecognizedReflectionTypeException
{
    public static function becausePropertyTypeIsUnrecognized(
        \ReflectionType $type,
        \ReflectionProperty $property,
        ?\Throwable $previous = null,
    ): self {
        return new self(
            type: $type,
            reflector: $property,
            message: \sprintf(
                'Reflection type "%s" of property "%s" is not supported',
                \addcslashes($type::class, '"'),
                \addcslashes($property->class . '::$' . $property->name, '"'),
            ),
            previous: $previous,
        );
    }
}

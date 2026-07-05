<?php

declare(strict_types=1);

namespace TypeLang\Reader\Exception;

/**
 * Occurs when the type of property cannot be recognized.
 */
final class UnrecognizedPropertyTypeException extends UnrecognizedReflectionTypeException
{
    public static function becausePropertyTypeIsUnrecognized(
        \ReflectionType $type,
        \ReflectionProperty $property,
        ?\Throwable $previous = null,
    ): self {
        $message = \sprintf(
            'Unsupported reflection type defined in $%s property: %s',
            $property->getName(),
            $type::class,
        );

        return new self($message, 0, $previous);
    }
}

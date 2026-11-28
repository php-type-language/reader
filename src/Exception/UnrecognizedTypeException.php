<?php

declare(strict_types=1);

namespace TypeLang\Reader\Exception;

/**
 * Occurs when a reflection type read alone cannot be recognized.
 */
final class UnrecognizedTypeException extends UnrecognizedReflectionTypeException
{
    public static function becauseTypeIsUnrecognized(\ReflectionType $type): self
    {
        return new self(
            type: $type,
            reflector: null,
            message: \sprintf('Reflection type "%s" is not supported', \addcslashes($type::class, '"')),
        );
    }
}

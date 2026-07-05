<?php

declare(strict_types=1);

namespace TypeLang\Reader\Exception;

/**
 * Occurs when a reflection type is not one of the supported kinds.
 */
final class UnrecognizedTypeException extends UnrecognizedReflectionTypeException
{
    public static function becauseTypeIsUnrecognized(\ReflectionType $type): self
    {
        $message = \sprintf('Unsupported reflection type: %s', $type::class);

        return new self($message);
    }
}

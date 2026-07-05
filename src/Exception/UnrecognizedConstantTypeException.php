<?php

declare(strict_types=1);

namespace TypeLang\Reader\Exception;

/**
 * Occurs when the type of class constant cannot be recognized.
 */
final class UnrecognizedConstantTypeException extends UnrecognizedReflectionTypeException
{
    public static function becauseConstantTypeIsUnrecognized(
        \ReflectionType $type,
        \ReflectionClassConstant $constant,
        ?\Throwable $previous = null,
    ): self {
        $message = \sprintf(
            'Unsupported reflection type defined in %s const: %s',
            $constant->getName(),
            $type::class,
        );

        return new self($message, 0, $previous);
    }
}

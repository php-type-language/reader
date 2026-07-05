<?php

declare(strict_types=1);

namespace TypeLang\Reader\Exception;

/**
 * Occurs when the type of function or method parameter cannot be recognized.
 */
final class UnrecognizedParameterTypeException extends UnrecognizedReflectionTypeException
{
    public static function becauseParameterTypeIsUnrecognized(
        \ReflectionType $type,
        \ReflectionParameter $parameter,
        ?\Throwable $previous = null,
    ): self {
        $message = \sprintf(
            'Unsupported reflection type defined in $%s parameter: %s',
            $parameter->getName(),
            $type::class,
        );

        return new self($message, 0, $previous);
    }
}

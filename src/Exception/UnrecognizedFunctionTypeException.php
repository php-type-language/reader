<?php

declare(strict_types=1);

namespace TypeLang\Reader\Exception;

/**
 * Occurs when the return type of function or method cannot be recognized.
 */
final class UnrecognizedFunctionTypeException extends UnrecognizedReflectionTypeException
{
    public static function becauseFunctionTypeIsUnrecognized(
        \ReflectionType $type,
        \ReflectionFunctionAbstract $function,
        ?\Throwable $previous = null,
    ): self {
        $message = \sprintf(
            'Unsupported reflection type defined in %s function: %s',
            $function->getName(),
            $type::class,
        );

        return new self($message, 0, $previous);
    }
}

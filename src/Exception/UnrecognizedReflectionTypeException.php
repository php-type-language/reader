<?php

declare(strict_types=1);

namespace TypeLang\ReflectionConverter\Exception;

class UnrecognizedReflectionTypeException extends ConverterException
{
    final public const ERROR_CODE_UNSUPPORTED_REFLECTION_TYPE = 0x01 + parent::CODE_LAST;

    protected const CODE_LAST = self::ERROR_CODE_UNSUPPORTED_REFLECTION_TYPE;

    public static function fromReflectionType(\ReflectionType $type): static
    {
        $message = \sprintf('Unsupported reflection type: %s', $type::class);

        return new static($message, self::ERROR_CODE_UNSUPPORTED_REFLECTION_TYPE);
    }
}

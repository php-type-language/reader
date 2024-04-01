<?php

declare(strict_types=1);

namespace TypeLang\Reader\Exception;

class UnrecognizedReflectionTypeException extends ReaderException
{
    final public const ERROR_CODE_INVALID_TYPE = 0x01 + parent::CODE_LAST;

    final public const ERROR_CODE_INVALID_TYPE_FROM_CONST = 0x02 + parent::CODE_LAST;

    final public const ERROR_CODE_INVALID_TYPE_FROM_PROPERTY = 0x03 + parent::CODE_LAST;

    final public const ERROR_CODE_INVALID_TYPE_FROM_FUNCTION = 0x04 + parent::CODE_LAST;

    final public const ERROR_CODE_INVALID_TYPE_FROM_PARAMETER = 0x05 + parent::CODE_LAST;

    protected const CODE_LAST = self::ERROR_CODE_INVALID_TYPE_FROM_PARAMETER;

    public static function fromReflectionType(\ReflectionType $type): static
    {
        $message = \sprintf('Unsupported reflection type: %s', $type::class);

        return new static($message, self::ERROR_CODE_INVALID_TYPE);
    }

    public static function fromReflectionConstant(\ReflectionType $type, \ReflectionClassConstant $const): static
    {
        $message = \vsprintf('Unsupported reflection type defined in %s const: %s', [
            $const->getName(),
            $type::class,
        ]);

        return new static($message, self::ERROR_CODE_INVALID_TYPE_FROM_CONST);
    }

    public static function fromReflectionProperty(\ReflectionType $type, \ReflectionProperty $property): static
    {
        $message = \vsprintf('Unsupported reflection type defined in $%s property: %s', [
            $property->getName(),
            $type::class,
        ]);

        return new static($message, self::ERROR_CODE_INVALID_TYPE_FROM_PROPERTY);
    }

    public static function fromReflectionFunction(\ReflectionType $type, \ReflectionFunctionAbstract $function): static
    {
        $message = \vsprintf('Unsupported reflection type defined in %s function: %s', [
            $function->getName(),
            $type::class,
        ]);

        return new static($message, self::ERROR_CODE_INVALID_TYPE_FROM_FUNCTION);
    }

    public static function fromReflectionParameter(\ReflectionType $type, \ReflectionParameter $parameter): static
    {
        $message = \vsprintf('Unsupported reflection type defined in $%s parameter: %s', [
            $parameter->getName(),
            $type::class,
        ]);

        return new static($message, self::ERROR_CODE_INVALID_TYPE_FROM_PARAMETER);
    }
}

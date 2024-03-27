<?php

declare(strict_types=1);

namespace TypeLang\ReflectionConverter\Exception;

/**
 * @psalm-consistent-constructor
 */
class ConverterException extends \LogicException implements ConverterExceptionInterface
{
    final public const ERROR_CODE_INTERNAL_ERROR = 0x01;

    protected const CODE_LAST = self::ERROR_CODE_INTERNAL_ERROR;

    public function __construct(string $message, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function fromInternalError(\Throwable $e): static
    {
        $message = 'An internal error occurred while converting reflection type';

        return new static($message, self::ERROR_CODE_INTERNAL_ERROR, $e);
    }
}

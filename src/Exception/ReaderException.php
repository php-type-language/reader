<?php

declare(strict_types=1);

namespace TypeLang\Reader\Exception;

class ReaderException extends \LogicException implements ReaderExceptionInterface
{
    public static function becauseInternalErrorOccurs(\Throwable $e): self
    {
        $message = 'An internal error occurred while reading reflection type';

        return new self($message, 0, $e);
    }
}

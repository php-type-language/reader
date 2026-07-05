<?php

declare(strict_types=1);

namespace TypeLang\Reader\Tests\Stub;

enum __ConstantReaderEnum: int
{
    case EXAMPLE = 0;
}

final class ConstantReaderStub
{
    public const int SINGLE = 0xDEAD_BEEF;

    public const int|string UNION = 0xDEAD_BEEF;

    public const __ConstantReaderEnum&\BackedEnum INTERSECTION = __ConstantReaderEnum::EXAMPLE;

    public const (__ConstantReaderEnum&\BackedEnum)|array COMPOSITE = __ConstantReaderEnum::EXAMPLE;

    public const ?int NULLABLE = null;

    public const UNTYPED = 0xDEAD_BEEF;
}

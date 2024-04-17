<?php

declare(strict_types=1);

namespace TypeLang\Reader\Tests\Unit\Reader\Stub;

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
}

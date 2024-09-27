<?php

declare(strict_types=1);

namespace TypeLang\Reader\Tests\Unit\Reader\Stub;

use TypeLang\Reader\Attribute\MapType;

enum __ConstantReaderEnum: int
{
    case EXAMPLE = 0;
}

final class ConstantReaderStub
{
    #[MapType('int')]
    public const int SINGLE = 0xDEAD_BEEF;
    #[MapType('string|int')]
    public const int|string UNION = 0xDEAD_BEEF;
    #[MapType('\TypeLang\Reader\Tests\Unit\Reader\Stub\__ConstantReaderEnum&\BackedEnum')]
    public const __ConstantReaderEnum&\BackedEnum INTERSECTION = __ConstantReaderEnum::EXAMPLE;
    #[MapType('(\TypeLang\Reader\Tests\Unit\Reader\Stub\__ConstantReaderEnum&\BackedEnum)|array')]
    public const (__ConstantReaderEnum&\BackedEnum)|array COMPOSITE = __ConstantReaderEnum::EXAMPLE;
}

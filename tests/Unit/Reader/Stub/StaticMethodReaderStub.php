<?php

declare(strict_types=1);

namespace TypeLang\Reader\Tests\Unit\Reader\Stub;

use TypeLang\Reader\Attribute\MapType;

interface StaticMethodReaderStub
{
    #[MapType('int')]
    public static function getSingleType(): int;
    #[MapType('string|int')]
    public static function getUnionType(): int|string;
    #[MapType('\ArrayAccess&\Traversable')]
    public static function getIntersectionType(): \ArrayAccess&\Traversable;
}

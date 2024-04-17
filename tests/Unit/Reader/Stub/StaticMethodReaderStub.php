<?php

declare(strict_types=1);

namespace TypeLang\Reader\Tests\Unit\Reader\Stub;

interface StaticMethodReaderStub
{
    public static function getSingleType(): int;
    public static function getUnionType(): int|string;
    public static function getIntersectionType(): \ArrayAccess&\Traversable;
}

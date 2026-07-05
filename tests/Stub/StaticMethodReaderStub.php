<?php

declare(strict_types=1);

namespace TypeLang\Reader\Tests\Stub;

interface StaticMethodReaderStub
{
    public static function getSingleType(): int;

    public static function getUnionType(): int|string;

    public static function getIntersectionType(): \ArrayAccess&\Traversable;

    public static function getCompositeType(): (\ArrayAccess&\Traversable)|array;
}

<?php

declare(strict_types=1);

namespace TypeLang\Reader\Tests\Stub;

final class ParameterReaderStub
{
    public static function withSingleType(int $param): void {}

    public static function withUnionType(int|string $param): void {}

    public static function withIntersectionType(\ArrayAccess&\Traversable $param): void {}

    public static function withCompositeType((\ArrayAccess&\Traversable)|array $param): void {}

    public static function withNullableType(?int $param): void {}

    public static function withoutType($param): void {}
}

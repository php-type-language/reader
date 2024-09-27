<?php

declare(strict_types=1);

namespace TypeLang\Reader\Tests\Unit\Reader\Stub;

use TypeLang\Reader\Attribute\MapType;

final class ParameterReaderStub
{
    public static function withSingleType(
        #[MapType('int')]
        int $param,
    ): void {}
    public static function withUnionType(
        #[MapType('string|int')]
        int|string $param,
    ): void {}
    public static function withIntersectionType(
        #[MapType('\ArrayAccess&\Traversable')]
        \ArrayAccess&\Traversable $param,
    ): void {}
}

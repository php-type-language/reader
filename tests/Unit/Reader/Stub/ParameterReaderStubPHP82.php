<?php

declare(strict_types=1);

namespace TypeLang\Reader\Tests\Unit\Reader\Stub;

use TypeLang\Reader\Attribute\MapType;

final class ParameterReaderStubPHP82
{
    public static function withCompositeType(
        #[MapType('(\ArrayAccess&\Traversable)|array')]
        (\ArrayAccess&\Traversable)|array $param,
    ): void {}
}

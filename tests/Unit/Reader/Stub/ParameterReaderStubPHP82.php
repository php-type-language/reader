<?php

declare(strict_types=1);

namespace TypeLang\Reader\Tests\Unit\Reader\Stub;

final class ParameterReaderStubPHP82
{
    public static function withCompositeType((\ArrayAccess&\Traversable)|array $param): void {}
}

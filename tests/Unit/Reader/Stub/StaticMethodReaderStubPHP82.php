<?php

declare(strict_types=1);

namespace TypeLang\Reader\Tests\Unit\Reader\Stub;

interface StaticMethodReaderStubPHP82
{
    public static function getCompositeType(): (\ArrayAccess&\Traversable)|array;
}

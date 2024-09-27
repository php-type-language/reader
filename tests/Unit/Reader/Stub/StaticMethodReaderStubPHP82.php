<?php

declare(strict_types=1);

namespace TypeLang\Reader\Tests\Unit\Reader\Stub;

use TypeLang\Reader\Attribute\MapType;

interface StaticMethodReaderStubPHP82
{
    #[MapType('(\ArrayAccess&\Traversable)|array')]
    public static function getCompositeType(): (\ArrayAccess&\Traversable)|array;
}

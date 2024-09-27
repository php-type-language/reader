<?php

declare(strict_types=1);

namespace TypeLang\Reader\Tests\Unit\Reader\Stub;

use TypeLang\Reader\Attribute\MapType;

final class PropertyReaderStubPHP82
{
    #[MapType('(\ArrayAccess&\Traversable)|array')]
    public (\ArrayAccess&\Traversable)|array $compositeType;
}

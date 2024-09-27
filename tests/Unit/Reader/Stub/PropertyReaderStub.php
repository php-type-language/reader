<?php

declare(strict_types=1);

namespace TypeLang\Reader\Tests\Unit\Reader\Stub;

use TypeLang\Reader\Attribute\MapType;

final class PropertyReaderStub
{
    #[MapType('int')]
    public int $singleType;
    #[MapType('string|int')]
    public int|string $unionType;
    #[MapType('\ArrayAccess&\Traversable')]
    public \ArrayAccess&\Traversable $intersectionType;
}

<?php

declare(strict_types=1);

namespace TypeLang\Reader\Tests\Unit\Reader\Stub;

final class PropertyReaderStub
{
    public int $singleType;
    public int|string $unionType;
    public \ArrayAccess&\Traversable $intersectionType;
}

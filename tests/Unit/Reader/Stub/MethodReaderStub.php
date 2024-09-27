<?php

declare(strict_types=1);

namespace TypeLang\Reader\Tests\Unit\Reader\Stub;

use TypeLang\Reader\Attribute\MapType;

interface MethodReaderStub
{
    #[MapType('int')]
    public function getSingleType(): int;
    #[MapType('string|int')]
    public function getUnionType(): int|string;
    #[MapType('\ArrayAccess&\Traversable')]
    public function getIntersectionType(): \ArrayAccess&\Traversable;
}

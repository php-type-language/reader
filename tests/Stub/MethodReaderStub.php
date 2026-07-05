<?php

declare(strict_types=1);

namespace TypeLang\Reader\Tests\Stub;

interface MethodReaderStub
{
    public function getSingleType(): int;

    public function getUnionType(): int|string;

    public function getIntersectionType(): \ArrayAccess&\Traversable;

    public function getCompositeType(): (\ArrayAccess&\Traversable)|array;
}

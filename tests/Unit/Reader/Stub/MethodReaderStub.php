<?php

declare(strict_types=1);

namespace TypeLang\Reader\Tests\Unit\Reader\Stub;

interface MethodReaderStub
{
    public function getSingleType(): int;
    public function getUnionType(): int|string;
    public function getIntersectionType(): \ArrayAccess&\Traversable;
}

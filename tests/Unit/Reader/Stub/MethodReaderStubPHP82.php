<?php

declare(strict_types=1);

namespace TypeLang\Reader\Tests\Unit\Reader\Stub;

interface MethodReaderStubPHP82
{
    public function getCompositeType(): (\ArrayAccess&\Traversable)|array;
}

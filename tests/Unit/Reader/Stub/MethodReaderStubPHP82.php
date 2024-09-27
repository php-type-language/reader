<?php

declare(strict_types=1);

namespace TypeLang\Reader\Tests\Unit\Reader\Stub;

use TypeLang\Reader\Attribute\MapType;

interface MethodReaderStubPHP82
{
    #[MapType('(\ArrayAccess&\Traversable)|array')]
    public function getCompositeType(): (\ArrayAccess&\Traversable)|array;
}

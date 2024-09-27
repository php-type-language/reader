<?php

declare(strict_types=1);

namespace TypeLang\Reader\Tests\Unit\Reader\Stub;

use TypeLang\Reader\Attribute\MapType;

#[MapType('(\ArrayAccess&\Traversable)|array')]
function get_composite_type(): (\ArrayAccess&\Traversable)|array {}

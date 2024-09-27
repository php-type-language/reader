<?php

declare(strict_types=1);

namespace TypeLang\Reader\Tests\Unit\Reader\Stub;

use TypeLang\Reader\Attribute\MapType;

#[MapType('int')]
function get_single_type(): int {}
#[MapType('string|int')]
function get_union_type(): int|string {}
#[MapType('\ArrayAccess&\Traversable')]
function get_intersection_type(): \ArrayAccess&\Traversable {}

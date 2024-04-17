<?php

declare(strict_types=1);

namespace TypeLang\Reader\Tests\Unit\Reader\Stub;

function get_single_type(): int {}
function get_union_type(): int|string {}
function get_intersection_type(): \ArrayAccess&\Traversable {}

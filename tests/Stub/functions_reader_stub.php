<?php

declare(strict_types=1);

namespace TypeLang\Reader\Tests\Stub;

function get_single_type(): int {}

function get_union_type(): int|string {}

function get_intersection_type(): \ArrayAccess&\Traversable {}

function get_composite_type(): (\ArrayAccess&\Traversable)|array {}

function get_nullable_type(): ?int {}

function get_void_type(): void {}

function get_no_type() {}

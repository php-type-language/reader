<?php

declare(strict_types=1);

namespace TypeLang\Reader\Tests\Stub;

final class PropertyReaderStub
{
    public int $singleType;

    public int|string $unionType;

    public \ArrayAccess&\Traversable $intersectionType;

    public ?int $nullableType;

    public int|float|string|null $unionWithNullType;

    public int|false $unionWithFalseType;

    public $untypedType;
}

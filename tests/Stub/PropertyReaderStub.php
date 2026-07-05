<?php

declare(strict_types=1);

namespace TypeLang\Reader\Tests\Stub;

final class PropertyReaderStub
{
    public int $singleType;

    public int|string $unionType;

    public \ArrayAccess&\Traversable $intersectionType;

    public (\ArrayAccess&\Traversable)|array $compositeType;

    public ?int $nullableType;

    public $untypedType;

    /**
     * An asymmetric property hook: reads as "string", but accepts a wider
     * "string|\Stringable" on write.
     */
    public string $hookedType {
        get => $this->hookedType;
        set(string|\Stringable $value) => (string) $value;
    }
}

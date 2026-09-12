<?php

declare(strict_types=1);

namespace TypeLang\Reader\Tests\Stub;

final class PropertyReaderStub84
{
    /**
     * An asymmetric property hook: reads as "string", but accepts a wider
     * "string|\Stringable" on write.
     */
    public string $hookedType {
        get => $this->hookedType;
        set(string|\Stringable $value) => (string) $value;
    }
}

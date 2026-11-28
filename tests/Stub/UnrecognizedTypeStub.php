<?php

declare(strict_types=1);

namespace TypeLang\Reader\Tests\Stub;

final class UnrecognizedTypeStub
{
    public const NAME = 'name';

    public string $name = '';

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}

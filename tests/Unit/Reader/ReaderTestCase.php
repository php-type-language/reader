<?php

declare(strict_types=1);

namespace TypeLang\Reader\Tests\Unit\Reader;

use PHPUnit\Framework\Attributes\Group;
use TypeLang\Reader\ReflectionReader;
use TypeLang\Reader\Tests\Unit\TestCase;

#[Group('unit'), Group('type-lang/reader')]
abstract class ReaderTestCase extends TestCase
{
    public static function readersDataProvider(): iterable
    {
        yield ReflectionReader::class => [new ReflectionReader()];
    }
}

<?php

declare(strict_types=1);

namespace TypeLang\Reader\Tests;

use PHPUnit\Framework\Attributes\Group;
use TypeLang\Reader\ReflectionReader;
use TypeLang\Type\Name;
use TypeLang\Type\NamedTypeNode;
use TypeLang\Type\TypeNode;

#[Group('type-lang/reader')]
abstract class ReaderTestCase extends TestCase
{
    protected static function assertSameType(?TypeNode $expected, ?TypeNode $actual, string $message = ''): void
    {
        self::assertEquals($expected, $actual, $message);
    }

    /**
     * A builtin (non-qualified) named type, e.g. "int" or "array".
     *
     * @param non-empty-string $name
     */
    protected static function builtin(string $name): NamedTypeNode
    {
        return new NamedTypeNode(Name::createFromString($name));
    }

    /**
     * A class-like (fully qualified) named type, e.g. "\ArrayAccess".
     *
     * @param non-empty-string $class
     */
    protected static function classType(string $class): NamedTypeNode
    {
        return new NamedTypeNode(Name::createFromString($class)->toFullQualified());
    }

    /**
     * @return iterable<non-empty-string, array{ReflectionReader}>
     */
    public static function readersDataProvider(): iterable
    {
        yield ReflectionReader::class => [new ReflectionReader()];
    }
}

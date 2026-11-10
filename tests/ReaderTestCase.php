<?php

declare(strict_types=1);

namespace TypeLang\Reader\Tests;

use PHPUnit\Framework\Attributes\Group;
use TypeLang\Reader\ReflectionReader;
use TypeLang\Type\Name;
use TypeLang\Type\NamespaceName;
use TypeLang\Type\NamespaceName\NamespaceKind;
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
        return new NamedTypeNode(new Name($name));
    }

    /**
     * A class-like (fully qualified) named type, e.g. "\ArrayAccess".
     *
     * @param non-empty-string $class
     */
    protected static function classType(string $class): NamedTypeNode
    {
        $separator = \strrpos($class, '\\');

        if ($separator === false) {
            return new NamedTypeNode(new Name($class, new NamespaceName('', NamespaceKind::FullyQualified)));
        }

        /** @var non-empty-string $word */
        $word = \substr($class, $separator + 1);

        /** @var non-empty-string $namespace */
        $namespace = \substr($class, 0, $separator);

        return new NamedTypeNode(new Name($word, new NamespaceName($namespace, NamespaceKind::FullyQualified)));
    }

    /**
     * @return iterable<non-empty-string, array{ReflectionReader}>
     */
    public static function readersDataProvider(): iterable
    {
        yield ReflectionReader::class => [new ReflectionReader()];
    }
}

<?php

declare(strict_types=1);

namespace TypeLang\Reader\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use TypeLang\Reader\ParameterReaderInterface;
use TypeLang\Reader\Tests\Stub\ParameterReaderStub;
use TypeLang\Type\IntersectionTypeNode;
use TypeLang\Type\NullableTypeNode;
use TypeLang\Type\UnionTypeNode;

#[Group('type-lang/reader')]
class ParameterReaderTest extends ReaderTestCase
{
    #[DataProvider('readersDataProvider')]
    public function testSimpleType(ParameterReaderInterface $reader): void
    {
        $type = $reader->findParameterType(
            parameter: new \ReflectionParameter(ParameterReaderStub::withSingleType(...), 0),
        );

        self::assertSameType(self::builtin('int'), $type);
    }

    #[DataProvider('readersDataProvider')]
    public function testUnionType(ParameterReaderInterface $reader): void
    {
        $type = $reader->findParameterType(
            parameter: new \ReflectionParameter(ParameterReaderStub::withUnionType(...), 0),
        );

        self::assertSameType(new UnionTypeNode(
            self::builtin('string'),
            self::builtin('int'),
        ), $type);
    }

    #[DataProvider('readersDataProvider')]
    public function testIntersectionType(ParameterReaderInterface $reader): void
    {
        $type = $reader->findParameterType(
            parameter: new \ReflectionParameter(ParameterReaderStub::withIntersectionType(...), 0),
        );

        self::assertSameType(new IntersectionTypeNode(
            self::classType(\ArrayAccess::class),
            self::classType(\Traversable::class),
        ), $type);
    }

    #[DataProvider('readersDataProvider')]
    public function testCompositeType(ParameterReaderInterface $reader): void
    {
        $type = $reader->findParameterType(
            parameter: new \ReflectionParameter(ParameterReaderStub::withCompositeType(...), 0),
        );

        self::assertSameType(new UnionTypeNode(
            new IntersectionTypeNode(
                self::classType(\ArrayAccess::class),
                self::classType(\Traversable::class),
            ),
            self::builtin('array'),
        ), $type);
    }

    #[DataProvider('readersDataProvider')]
    public function testNullableType(ParameterReaderInterface $reader): void
    {
        $type = $reader->findParameterType(
            parameter: new \ReflectionParameter(ParameterReaderStub::withNullableType(...), 0),
        );

        self::assertSameType(new NullableTypeNode(self::builtin('int')), $type);
    }

    #[DataProvider('readersDataProvider')]
    public function testUntypedParameterHasNoType(ParameterReaderInterface $reader): void
    {
        $type = $reader->findParameterType(
            parameter: new \ReflectionParameter(ParameterReaderStub::withoutType(...), 0),
        );

        self::assertNull($type);
    }
}

<?php

declare(strict_types=1);

namespace TypeLang\Reader\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use TypeLang\Reader\PropertyAccessDirection;
use TypeLang\Reader\PropertyReaderInterface;
use TypeLang\Reader\Tests\Stub\PropertyReaderStub;
use TypeLang\Type\IntersectionTypeNode;
use TypeLang\Type\NullableTypeNode;
use TypeLang\Type\UnionTypeNode;

#[Group('type-lang/reader')]
class PropertyReaderTest extends ReaderTestCase
{
    #[DataProvider('readersDataProvider')]
    public function testSimpleType(PropertyReaderInterface $reader): void
    {
        $type = $reader->findPropertyType(
            property: new \ReflectionProperty(PropertyReaderStub::class, 'singleType'),
        );

        self::assertSameType(self::builtin('int'), $type);
    }

    #[DataProvider('readersDataProvider')]
    public function testUnionType(PropertyReaderInterface $reader): void
    {
        $type = $reader->findPropertyType(
            property: new \ReflectionProperty(PropertyReaderStub::class, 'unionType'),
        );

        self::assertSameType(new UnionTypeNode(
            self::builtin('string'),
            self::builtin('int'),
        ), $type);
    }

    #[DataProvider('readersDataProvider')]
    public function testIntersectionType(PropertyReaderInterface $reader): void
    {
        $type = $reader->findPropertyType(
            property: new \ReflectionProperty(PropertyReaderStub::class, 'intersectionType'),
        );

        self::assertSameType(new IntersectionTypeNode(
            self::classType(\ArrayAccess::class),
            self::classType(\Traversable::class),
        ), $type);
    }

    #[DataProvider('readersDataProvider')]
    public function testCompositeType(PropertyReaderInterface $reader): void
    {
        $type = $reader->findPropertyType(
            property: new \ReflectionProperty(PropertyReaderStub::class, 'compositeType'),
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
    public function testNullableType(PropertyReaderInterface $reader): void
    {
        $type = $reader->findPropertyType(
            property: new \ReflectionProperty(PropertyReaderStub::class, 'nullableType'),
        );

        self::assertSameType(new NullableTypeNode(self::builtin('int')), $type);
    }

    #[DataProvider('readersDataProvider')]
    public function testUntypedPropertyHasNoType(PropertyReaderInterface $reader): void
    {
        $type = $reader->findPropertyType(
            property: new \ReflectionProperty(PropertyReaderStub::class, 'untypedType'),
        );

        self::assertNull($type);
    }

    #[DataProvider('readersDataProvider')]
    public function testHookedPropertyReadType(PropertyReaderInterface $reader): void
    {
        $type = $reader->findPropertyType(
            property: new \ReflectionProperty(PropertyReaderStub::class, 'hookedType'),
            access: PropertyAccessDirection::Read,
        );

        self::assertSameType(self::builtin('string'), $type);
    }

    #[DataProvider('readersDataProvider')]
    public function testHookedPropertyWriteType(PropertyReaderInterface $reader): void
    {
        $type = $reader->findPropertyType(
            property: new \ReflectionProperty(PropertyReaderStub::class, 'hookedType'),
            access: PropertyAccessDirection::Write,
        );

        // The "set" hook widens the accepted type to "string|\Stringable".
        self::assertSameType(new UnionTypeNode(
            self::classType(\Stringable::class),
            self::builtin('string'),
        ), $type);
    }

    #[DataProvider('readersDataProvider')]
    public function testWriteTypeFallsBackToReadTypeWithoutHook(PropertyReaderInterface $reader): void
    {
        $type = $reader->findPropertyType(
            property: new \ReflectionProperty(PropertyReaderStub::class, 'singleType'),
            access: PropertyAccessDirection::Write,
        );

        self::assertSameType(self::builtin('int'), $type);
    }
}

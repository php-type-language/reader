<?php

declare(strict_types=1);

namespace TypeLang\Reader\Tests\Unit\Reader;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RequiresPhp;
use TypeLang\Parser\Node\FullQualifiedName;
use TypeLang\Parser\Node\Identifier;
use TypeLang\Parser\Node\Stmt\IntersectionTypeNode;
use TypeLang\Parser\Node\Stmt\NamedTypeNode;
use TypeLang\Parser\Node\Stmt\UnionTypeNode;
use TypeLang\Reader\PropertyReaderInterface;
use TypeLang\Reader\Tests\Unit\Reader\Stub\PropertyReaderStub;
use TypeLang\Reader\Tests\Unit\Reader\Stub\PropertyReaderStubPHP82;

#[Group('unit'), Group('type-lang/reader')]
class PropertyReaderTest extends ReaderTestCase
{
    #[DataProvider('readersDataProvider')]
    public function testSimpleType(PropertyReaderInterface $reader): void
    {
        $type = $reader->findPropertyType(
            property: new \ReflectionProperty(PropertyReaderStub::class, 'singleType'),
        );

        self::assertEquals(new NamedTypeNode(
            name: new Identifier('int'),
        ), $type);
    }

    #[DataProvider('readersDataProvider')]
    public function testUnionType(PropertyReaderInterface $reader): void
    {
        $type = $reader->findPropertyType(
            property: new \ReflectionProperty(PropertyReaderStub::class, 'unionType'),
        );

        self::assertEquals(new UnionTypeNode(
            a: new NamedTypeNode('string'),
            b: new NamedTypeNode('int'),
        ), $type);
    }

    #[DataProvider('readersDataProvider')]
    public function testIntersectionType(PropertyReaderInterface $reader): void
    {
        $type = $reader->findPropertyType(
            property: new \ReflectionProperty(PropertyReaderStub::class, 'intersectionType'),
        );

        self::assertEquals(new IntersectionTypeNode(
            a: new NamedTypeNode(new FullQualifiedName(\ArrayAccess::class)),
            b: new NamedTypeNode(new FullQualifiedName(\Traversable::class)),
        ), $type);
    }

    #[RequiresPhp('>=8.2')]
    #[DataProvider('readersDataProvider')]
    public function testCompositeType(PropertyReaderInterface $reader): void
    {
        $type = $reader->findPropertyType(
            property: new \ReflectionProperty(PropertyReaderStubPHP82::class, 'compositeType'),
        );

        self::assertEquals(new UnionTypeNode(
            a: new IntersectionTypeNode(
                a: new NamedTypeNode(new FullQualifiedName(\ArrayAccess::class)),
                b: new NamedTypeNode(new FullQualifiedName(\Traversable::class)),
            ),
            b: new NamedTypeNode('array'),
        ), $type);
    }
}

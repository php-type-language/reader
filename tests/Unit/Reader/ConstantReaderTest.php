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
use TypeLang\Reader\ConstantReaderInterface;
use TypeLang\Reader\Tests\Unit\Reader\Stub\__ConstantReaderEnum;
use TypeLang\Reader\Tests\Unit\Reader\Stub\ConstantReaderStub;

#[Group('unit'), Group('type-lang/reader')]
class ConstantReaderTest extends ReaderTestCase
{
    #[RequiresPhp('>=8.3')]
    #[DataProvider('readersDataProvider')]
    public function testSimpleType(ConstantReaderInterface $reader): void
    {
        $type = $reader->findConstantType(
            constant: new \ReflectionClassConstant(ConstantReaderStub::class, 'SINGLE'),
        );

        self::assertEquals(new NamedTypeNode(
            name: new Identifier('int'),
        ), $type);
    }

    #[RequiresPhp('>=8.3')]
    #[DataProvider('readersDataProvider')]
    public function testUnionType(ConstantReaderInterface $reader): void
    {
        $type = $reader->findConstantType(
            constant: new \ReflectionClassConstant(ConstantReaderStub::class, 'UNION'),
        );

        self::assertEquals(new UnionTypeNode(
            a: new NamedTypeNode('string'),
            b: new NamedTypeNode('int'),
        ), $type);
    }

    #[RequiresPhp('>=8.3')]
    #[DataProvider('readersDataProvider')]
    public function testIntersectionType(ConstantReaderInterface $reader): void
    {
        $type = $reader->findConstantType(
            constant: new \ReflectionClassConstant(ConstantReaderStub::class, 'INTERSECTION'),
        );

        self::assertEquals(new IntersectionTypeNode(
            a: new NamedTypeNode(new FullQualifiedName(__ConstantReaderEnum::class)),
            b: new NamedTypeNode(new FullQualifiedName(\BackedEnum::class)),
        ), $type);
    }

    #[RequiresPhp('>=8.3')]
    #[DataProvider('readersDataProvider')]
    public function testCompositeType(ConstantReaderInterface $reader): void
    {
        $type = $reader->findConstantType(
            constant: new \ReflectionClassConstant(ConstantReaderStub::class, 'COMPOSITE'),
        );

        self::assertEquals(new UnionTypeNode(
            a: new IntersectionTypeNode(
                a: new NamedTypeNode(new FullQualifiedName(__ConstantReaderEnum::class)),
                b: new NamedTypeNode(new FullQualifiedName(\BackedEnum::class)),
            ),
            b: new NamedTypeNode('array'),
        ), $type);
    }
}

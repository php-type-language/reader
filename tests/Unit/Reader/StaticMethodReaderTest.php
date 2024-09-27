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
use TypeLang\Reader\FunctionReaderInterface;
use TypeLang\Reader\Tests\Unit\Reader\Stub\StaticMethodReaderStub;
use TypeLang\Reader\Tests\Unit\Reader\Stub\StaticMethodReaderStubPHP82;

#[Group('unit'), Group('type-lang/reader')]
class StaticMethodReaderTest extends ReaderTestCase
{
    #[DataProvider('readersDataProvider')]
    public function testSimpleType(FunctionReaderInterface $reader): void
    {
        $type = $reader->findFunctionType(
            function: new \ReflectionMethod(StaticMethodReaderStub::class, 'getSingleType'),
        );

        self::assertSameType(new NamedTypeNode(
            name: new Identifier('int'),
        ), $type);
    }

    #[DataProvider('readersDataProvider')]
    public function testUnionType(FunctionReaderInterface $reader): void
    {
        $type = $reader->findFunctionType(
            function: new \ReflectionMethod(StaticMethodReaderStub::class, 'getUnionType'),
        );

        self::assertSameType(new UnionTypeNode(
            a: new NamedTypeNode('string'),
            b: new NamedTypeNode('int'),
        ), $type);
    }

    #[DataProvider('readersDataProvider')]
    public function testIntersectionType(FunctionReaderInterface $reader): void
    {
        $type = $reader->findFunctionType(
            function: new \ReflectionMethod(StaticMethodReaderStub::class, 'getIntersectionType'),
        );

        self::assertSameType(new IntersectionTypeNode(
            a: new NamedTypeNode(new FullQualifiedName(\ArrayAccess::class)),
            b: new NamedTypeNode(new FullQualifiedName(\Traversable::class)),
        ), $type);
    }

    #[RequiresPhp('>=8.2')]
    #[DataProvider('readersDataProvider')]
    public function testCompositeType(FunctionReaderInterface $reader): void
    {
        $type = $reader->findFunctionType(
            function: new \ReflectionMethod(StaticMethodReaderStubPHP82::class, 'getCompositeType'),
        );

        self::assertSameType(new UnionTypeNode(
            a: new IntersectionTypeNode(
                a: new NamedTypeNode(new FullQualifiedName(\ArrayAccess::class)),
                b: new NamedTypeNode(new FullQualifiedName(\Traversable::class)),
            ),
            b: new NamedTypeNode('array'),
        ), $type);
    }
}

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

#[Group('unit'), Group('type-lang/reader')]
class FunctionReaderTest extends ReaderTestCase
{
    #[DataProvider('readersDataProvider')]
    public function testSimpleType(FunctionReaderInterface $reader): void
    {
        require_once __DIR__ . '/Stub/functions_reader_stub.php';

        $type = $reader->findFunctionType(
            function: new \ReflectionFunction('TypeLang\Reader\Tests\Unit\Reader\Stub\get_single_type'),
        );

        self::assertSameType(new NamedTypeNode(
            name: new Identifier('int'),
        ), $type);
    }

    #[DataProvider('readersDataProvider')]
    public function testUnionType(FunctionReaderInterface $reader): void
    {
        require_once __DIR__ . '/Stub/functions_reader_stub.php';

        $type = $reader->findFunctionType(
            function: new \ReflectionFunction('TypeLang\Reader\Tests\Unit\Reader\Stub\get_union_type'),
        );

        self::assertSameType(new UnionTypeNode(
            a: new NamedTypeNode('string'),
            b: new NamedTypeNode('int'),
        ), $type);
    }

    #[DataProvider('readersDataProvider')]
    public function testIntersectionType(FunctionReaderInterface $reader): void
    {
        require_once __DIR__ . '/Stub/functions_reader_stub.php';

        $type = $reader->findFunctionType(
            function: new \ReflectionFunction('TypeLang\Reader\Tests\Unit\Reader\Stub\get_intersection_type'),
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
        require_once __DIR__ . '/Stub/functions_reader_stub_php82.php';

        $type = $reader->findFunctionType(
            function: new \ReflectionFunction('TypeLang\Reader\Tests\Unit\Reader\Stub\get_composite_type'),
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

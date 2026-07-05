<?php

declare(strict_types=1);

namespace TypeLang\Reader\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use TypeLang\Reader\FunctionReaderInterface;
use TypeLang\Type\IntersectionTypeNode;
use TypeLang\Type\NullableTypeNode;
use TypeLang\Type\UnionTypeNode;

#[Group('type-lang/reader')]
class FunctionReaderTest extends ReaderTestCase
{
    protected function setUp(): void
    {
        require_once __DIR__ . '/Stub/functions_reader_stub.php';
    }

    #[DataProvider('readersDataProvider')]
    public function testSimpleType(FunctionReaderInterface $reader): void
    {
        $type = $reader->findFunctionType(
            function: new \ReflectionFunction('TypeLang\Reader\Tests\Stub\get_single_type'),
        );

        self::assertSameType(self::builtin('int'), $type);
    }

    #[DataProvider('readersDataProvider')]
    public function testUnionType(FunctionReaderInterface $reader): void
    {
        $type = $reader->findFunctionType(
            function: new \ReflectionFunction('TypeLang\Reader\Tests\Stub\get_union_type'),
        );

        self::assertSameType(new UnionTypeNode(
            self::builtin('string'),
            self::builtin('int'),
        ), $type);
    }

    #[DataProvider('readersDataProvider')]
    public function testIntersectionType(FunctionReaderInterface $reader): void
    {
        $type = $reader->findFunctionType(
            function: new \ReflectionFunction('TypeLang\Reader\Tests\Stub\get_intersection_type'),
        );

        self::assertSameType(new IntersectionTypeNode(
            self::classType(\ArrayAccess::class),
            self::classType(\Traversable::class),
        ), $type);
    }

    #[DataProvider('readersDataProvider')]
    public function testCompositeType(FunctionReaderInterface $reader): void
    {
        $type = $reader->findFunctionType(
            function: new \ReflectionFunction('TypeLang\Reader\Tests\Stub\get_composite_type'),
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
    public function testNullableType(FunctionReaderInterface $reader): void
    {
        $type = $reader->findFunctionType(
            function: new \ReflectionFunction('TypeLang\Reader\Tests\Stub\get_nullable_type'),
        );

        self::assertSameType(new NullableTypeNode(self::builtin('int')), $type);
    }

    #[DataProvider('readersDataProvider')]
    public function testVoidType(FunctionReaderInterface $reader): void
    {
        $type = $reader->findFunctionType(
            function: new \ReflectionFunction('TypeLang\Reader\Tests\Stub\get_void_type'),
        );

        self::assertSameType(self::builtin('void'), $type);
    }

    #[DataProvider('readersDataProvider')]
    public function testNoReturnTypeHasNoType(FunctionReaderInterface $reader): void
    {
        $type = $reader->findFunctionType(
            function: new \ReflectionFunction('TypeLang\Reader\Tests\Stub\get_no_type'),
        );

        self::assertNull($type);
    }
}

<?php

declare(strict_types=1);

namespace TypeLang\Reader\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use TypeLang\Reader\FunctionReaderInterface;
use TypeLang\Reader\Tests\Stub\MethodReaderStub;
use TypeLang\Type\IntersectionTypeNode;
use TypeLang\Type\UnionTypeNode;

#[Group('type-lang/reader')]
class MethodReaderTest extends ReaderTestCase
{
    #[DataProvider('readersDataProvider')]
    public function testSimpleType(FunctionReaderInterface $reader): void
    {
        $type = $reader->findFunctionType(
            function: new \ReflectionMethod(MethodReaderStub::class, 'getSingleType'),
        );

        self::assertSameType(self::builtin('int'), $type);
    }

    #[DataProvider('readersDataProvider')]
    public function testUnionType(FunctionReaderInterface $reader): void
    {
        $type = $reader->findFunctionType(
            function: new \ReflectionMethod(MethodReaderStub::class, 'getUnionType'),
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
            function: new \ReflectionMethod(MethodReaderStub::class, 'getIntersectionType'),
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
            function: new \ReflectionMethod(MethodReaderStub::class, 'getCompositeType'),
        );

        self::assertSameType(new UnionTypeNode(
            new IntersectionTypeNode(
                self::classType(\ArrayAccess::class),
                self::classType(\Traversable::class),
            ),
            self::builtin('array'),
        ), $type);
    }
}

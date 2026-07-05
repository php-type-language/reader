<?php

declare(strict_types=1);

namespace TypeLang\Reader\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use TypeLang\Reader\ConstantReaderInterface;
use TypeLang\Reader\Tests\Stub\__ConstantReaderEnum;
use TypeLang\Reader\Tests\Stub\ConstantReaderStub;
use TypeLang\Type\IntersectionTypeNode;
use TypeLang\Type\NullableTypeNode;
use TypeLang\Type\UnionTypeNode;

#[Group('type-lang/reader')]
class ConstantReaderTest extends ReaderTestCase
{
    #[DataProvider('readersDataProvider')]
    public function testSimpleType(ConstantReaderInterface $reader): void
    {
        $type = $reader->findConstantType(
            constant: new \ReflectionClassConstant(ConstantReaderStub::class, 'SINGLE'),
        );

        self::assertSameType(self::builtin('int'), $type);
    }

    #[DataProvider('readersDataProvider')]
    public function testUnionType(ConstantReaderInterface $reader): void
    {
        $type = $reader->findConstantType(
            constant: new \ReflectionClassConstant(ConstantReaderStub::class, 'UNION'),
        );

        self::assertSameType(new UnionTypeNode(
            self::builtin('string'),
            self::builtin('int'),
        ), $type);
    }

    #[DataProvider('readersDataProvider')]
    public function testIntersectionType(ConstantReaderInterface $reader): void
    {
        $type = $reader->findConstantType(
            constant: new \ReflectionClassConstant(ConstantReaderStub::class, 'INTERSECTION'),
        );

        self::assertSameType(new IntersectionTypeNode(
            self::classType(__ConstantReaderEnum::class),
            self::classType(\BackedEnum::class),
        ), $type);
    }

    #[DataProvider('readersDataProvider')]
    public function testCompositeType(ConstantReaderInterface $reader): void
    {
        $type = $reader->findConstantType(
            constant: new \ReflectionClassConstant(ConstantReaderStub::class, 'COMPOSITE'),
        );

        self::assertSameType(new UnionTypeNode(
            new IntersectionTypeNode(
                self::classType(__ConstantReaderEnum::class),
                self::classType(\BackedEnum::class),
            ),
            self::builtin('array'),
        ), $type);
    }

    #[DataProvider('readersDataProvider')]
    public function testNullableType(ConstantReaderInterface $reader): void
    {
        $type = $reader->findConstantType(
            constant: new \ReflectionClassConstant(ConstantReaderStub::class, 'NULLABLE'),
        );

        self::assertSameType(new NullableTypeNode(self::builtin('int')), $type);
    }

    #[DataProvider('readersDataProvider')]
    public function testUntypedConstantHasNoType(ConstantReaderInterface $reader): void
    {
        $type = $reader->findConstantType(
            constant: new \ReflectionClassConstant(ConstantReaderStub::class, 'UNTYPED'),
        );

        self::assertNull($type);
    }
}

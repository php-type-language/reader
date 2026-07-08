<?php

declare(strict_types=1);

namespace TypeLang\Reader\Tests;

use PHPUnit\Framework\Attributes\Group;
use TypeLang\Reader\Exception\UnrecognizedConstantTypeException;
use TypeLang\Reader\Exception\UnrecognizedReflectionTypeException;
use TypeLang\Reader\Exception\UnrecognizedTypeException;
use TypeLang\Reader\ReflectionReader;
use TypeLang\Reader\Tests\Stub\ConstantReaderStub;

#[Group('type-lang/reader')]
class ReflectionReaderTest extends ReaderTestCase
{
    public function testGetTypeConvertsNamedType(): void
    {
        $reader = new ReflectionReader();

        $type = $reader->getType(
            new \ReflectionProperty(new class {
                public int $value;
            }, 'value')->getType(),
        );

        self::assertSameType(self::builtin('int'), $type);
    }

    public function testUnsupportedReflectionTypeThrowsTerminalException(): void
    {
        $reader = new ReflectionReader();

        $unsupported = new class extends \ReflectionType {
            #[\Override]
            public function allowsNull(): bool
            {
                return false;
            }

            #[\Override]
            public function __toString(): string
            {
                return 'unsupported';
            }
        };

        try {
            $reader->getType($unsupported);
            self::fail('Expected an UnrecognizedTypeException to be thrown');
        } catch (UnrecognizedTypeException $e) {
            self::assertInstanceOf(UnrecognizedReflectionTypeException::class, $e);
            self::assertTrue(new \ReflectionClass($e)->isFinal());
        }
    }

    /**
     * The "mixed" type already includes "null", so it must not be wrapped into
     * a {@see \TypeLang\Type\NullableTypeNode} ("?mixed" is not even valid PHP).
     *
     * Currently {@see ReflectionReader::convertNamedType()} wraps it, because
     * {@see \ReflectionNamedType::allowsNull()} returns "true" for "mixed".
     */
    public function testMixedTypeIsNotNullable(): void
    {
        // @phpstan-ignore-next-line deadCode.unreachable
        $reader = new ReflectionReader();

        $type = $reader->getType(
            new \ReflectionProperty(new class {
                public mixed $value;
            }, 'value')->getType(),
        );

        self::assertSameType(self::builtin('mixed'), $type);
    }

    public function testContextualExceptionWrapsAndChainsRootCause(): void
    {
        $reader = new ReflectionReader();

        $constant = new \ReflectionClassConstant(ConstantReaderStub::class, 'SINGLE');

        $previous = UnrecognizedTypeException::becauseTypeIsUnrecognized($constant->getType());
        $exception = UnrecognizedConstantTypeException::becauseConstantTypeIsUnrecognized(
            $constant->getType(),
            $constant,
            $previous,
        );

        self::assertInstanceOf(UnrecognizedReflectionTypeException::class, $exception);
        self::assertTrue(new \ReflectionClass($exception)->isFinal());
        self::assertSame($previous, $exception->getPrevious());
        self::assertStringContainsString('SINGLE', $exception->getMessage());
    }
}

<?php

declare(strict_types=1);

namespace TypeLang\Reader\Tests\Exception;

use TypeLang\Reader\Exception\ReaderExceptionInterface;
use TypeLang\Reader\Exception\UnrecognizedConstantTypeException;
use TypeLang\Reader\Exception\UnrecognizedFunctionTypeException;
use TypeLang\Reader\Exception\UnrecognizedParameterTypeException;
use TypeLang\Reader\Exception\UnrecognizedPropertyTypeException;
use TypeLang\Reader\Exception\UnrecognizedReflectionTypeException;
use TypeLang\Reader\Exception\UnrecognizedTypeException;
use TypeLang\Reader\Tests\Stub\UnrecognizedTypeStub;
use TypeLang\Reader\Tests\TestCase;

final class UnrecognizedReflectionTypeExceptionTest extends TestCase
{
    private const STUB = UnrecognizedTypeStub::class;

    public function testTypeReadAloneHasNoReflector(): void
    {
        $type = self::createType();
        $error = UnrecognizedTypeException::becauseTypeIsUnrecognized($type);

        self::assertSame($type, $error->type);
        self::assertNull($error->reflector);
        self::assertSame(\sprintf('Reflection type "%s" is not supported', $type::class), $error->getMessage());
    }

    public function testPropertyTypeKeepsTheProperty(): void
    {
        $property = new \ReflectionProperty(self::STUB, 'name');

        self::assertError(
            UnrecognizedPropertyTypeException::becausePropertyTypeIsUnrecognized(...),
            $property,
            'property "' . self::STUB . '::$name"',
        );
    }

    public function testParameterTypeKeepsTheParameter(): void
    {
        $parameter = new \ReflectionParameter([self::STUB, 'setName'], 'name');

        self::assertError(
            UnrecognizedParameterTypeException::becauseParameterTypeIsUnrecognized(...),
            $parameter,
            'parameter "' . self::STUB . '::setName($name)"',
        );
    }

    public function testFunctionTypeKeepsTheFunction(): void
    {
        $function = new \ReflectionMethod(self::STUB, 'setName');

        self::assertError(
            UnrecognizedFunctionTypeException::becauseFunctionTypeIsUnrecognized(...),
            $function,
            'function "' . self::STUB . '::setName()"',
        );
    }

    public function testFunctionTypeOfPlainFunctionHasNoClass(): void
    {
        $error = UnrecognizedFunctionTypeException::becauseFunctionTypeIsUnrecognized(
            self::createType(),
            new \ReflectionFunction('strlen'),
        );

        self::assertStringContainsString('of function "strlen()"', $error->getMessage());
    }

    public function testConstantTypeKeepsTheConstant(): void
    {
        $constant = new \ReflectionClassConstant(self::STUB, 'NAME');

        self::assertError(
            UnrecognizedConstantTypeException::becauseConstantTypeIsUnrecognized(...),
            $constant,
            'constant "' . self::STUB . '::NAME"',
        );
    }

    /**
     * @param \Closure(\ReflectionType, \Reflector, \Throwable): UnrecognizedReflectionTypeException $create
     * @param non-empty-string $element
     */
    private static function assertError(\Closure $create, \Reflector $reflector, string $element): void
    {
        $type = self::createType();
        $previous = UnrecognizedTypeException::becauseTypeIsUnrecognized($type);
        $error = $create($type, $reflector, $previous);

        self::assertInstanceOf(ReaderExceptionInterface::class, $error);
        self::assertSame($type, $error->type);
        self::assertSame($reflector, $error->reflector);
        self::assertSame($previous, $error->getPrevious());
        self::assertSame(
            \sprintf('Reflection type "%s" of %s is not supported', $type::class, $element),
            $error->getMessage(),
        );
    }

    private static function createType(): \ReflectionType
    {
        return new class extends \ReflectionType {
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
    }
}

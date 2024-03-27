<?php

declare(strict_types=1);

namespace TypeLang\ReflectionConverter;

use TypeLang\Parser\Node\FullQualifiedName;
use TypeLang\Parser\Node\Identifier;
use TypeLang\Parser\Node\Name;
use TypeLang\Parser\Node\Stmt\IntersectionTypeNode;
use TypeLang\Parser\Node\Stmt\NamedTypeNode;
use TypeLang\Parser\Node\Stmt\NullableTypeNode;
use TypeLang\Parser\Node\Stmt\TypeStatement;
use TypeLang\Parser\Node\Stmt\UnionTypeNode;
use TypeLang\ReflectionConverter\Exception\ConverterExceptionInterface;
use TypeLang\ReflectionConverter\Exception\UnrecognizedReflectionTypeException;

final class Converter implements ConverterInterface
{
    /**
     * @api
     *
     * @throws ConverterExceptionInterface
     */
    public function convertPropertyType(\ReflectionProperty $property): ?TypeStatement
    {
        $type = $property->getType();

        if ($type instanceof \ReflectionType) {
            return $this->convert($type);
        }

        return null;
    }

    /**
     * @api
     *
     * @throws ConverterExceptionInterface
     */
    public function convertFunctionType(\ReflectionFunctionAbstract $function): ?TypeStatement
    {
        $type = $function->getReturnType();

        if ($type instanceof \ReflectionType) {
            return $this->convert($type);
        }

        return null;
    }

    /**
     * @api
     *
     * @throws ConverterExceptionInterface
     */
    public function convertParameterType(\ReflectionParameter $parameter): ?TypeStatement
    {
        $type = $parameter->getType();

        if ($type instanceof \ReflectionType) {
            return $this->convert($type);
        }

        return null;
    }

    /**
     * @api
     *
     * @throws ConverterExceptionInterface
     */
    public function convertConstantType(\ReflectionClassConstant $constant): ?TypeStatement
    {
        if (\PHP_VERSION_ID < 80300) {
            return null;
        }

        /** @var \ReflectionType|null $type */
        $type = $constant->getType();

        if ($type instanceof \ReflectionType) {
            return $this->convert($type);
        }

        return null;
    }

    public function convert(\ReflectionType $type): TypeStatement
    {
        return match (true) {
            $type instanceof \ReflectionUnionType => $this->convertUnionType($type),
            $type instanceof \ReflectionIntersectionType => $this->convertIntersectionType($type),
            $type instanceof \ReflectionNamedType => $this->convertNamedType($type),
            default => throw UnrecognizedReflectionTypeException::fromReflectionType($type),
        };
    }

    private function convertNamedType(\ReflectionNamedType $type): TypeStatement
    {
        $result = $this->convertNonNullNamedType($type);

        if ($type->allowsNull() && $type->getName() !== 'null') {
            return new NullableTypeNode($result);
        }

        return $result;
    }

    private function convertNonNullNamedType(\ReflectionNamedType $type): TypeStatement
    {
        $identifier = new Identifier($type->getName());

        if ($type->isBuiltin() || $identifier->isSpecial() || $identifier->isBuiltin()) {
            return new NamedTypeNode(new Name($identifier));
        }

        return new NamedTypeNode(new FullQualifiedName($identifier));
    }

    /**
     * @throws ConverterExceptionInterface
     */
    private function convertUnionType(\ReflectionUnionType $type): UnionTypeNode
    {
        $children = [];

        foreach ($type->getTypes() as $child) {
            $children[] = $this->convert($child);
        }

        return new UnionTypeNode(...$children);
    }

    /**
     * @throws ConverterExceptionInterface
     */
    private function convertIntersectionType(\ReflectionIntersectionType $type): IntersectionTypeNode
    {
        $children = [];

        foreach ($type->getTypes() as $child) {
            $children[] = $this->convert($child);
        }

        return new IntersectionTypeNode(...$children);
    }
}

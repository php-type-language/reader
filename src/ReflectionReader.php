<?php

declare(strict_types=1);

namespace TypeLang\Reader;

use TypeLang\Parser\Node\FullQualifiedName;
use TypeLang\Parser\Node\Identifier;
use TypeLang\Parser\Node\Name;
use TypeLang\Parser\Node\Stmt\IntersectionTypeNode;
use TypeLang\Parser\Node\Stmt\NamedTypeNode;
use TypeLang\Parser\Node\Stmt\NullableTypeNode;
use TypeLang\Parser\Node\Stmt\TypeStatement;
use TypeLang\Parser\Node\Stmt\UnionTypeNode;
use TypeLang\Reader\Exception\ReaderExceptionInterface;
use TypeLang\Reader\Exception\UnrecognizedReflectionTypeException;

final class ReflectionReader implements ReaderInterface
{
    public function findConstantType(\ReflectionClassConstant $constant): ?TypeStatement
    {
        if (\PHP_VERSION_ID < 80300) {
            return null;
        }

        /** @var \ReflectionType|null $type */
        $type = $constant->getType();

        if ($type instanceof \ReflectionType) {
            try {
                return $this->getType($type);
            } catch (UnrecognizedReflectionTypeException) {
                throw UnrecognizedReflectionTypeException::fromReflectionConstant($type, $constant);
            }
        }

        return null;
    }

    public function findPropertyType(\ReflectionProperty $property): ?TypeStatement
    {
        $type = $property->getType();

        if ($type instanceof \ReflectionType) {
            try {
                return $this->getType($type);
            } catch (UnrecognizedReflectionTypeException) {
                throw UnrecognizedReflectionTypeException::fromReflectionProperty($type, $property);
            }
        }

        return null;
    }

    public function findFunctionType(\ReflectionFunctionAbstract $function): ?TypeStatement
    {
        $type = $function->getReturnType();

        if ($type instanceof \ReflectionType) {
            try {
                return $this->getType($type);
            } catch (UnrecognizedReflectionTypeException) {
                throw UnrecognizedReflectionTypeException::fromReflectionFunction($type, $function);
            }
        }

        return null;
    }

    public function findParameterType(\ReflectionParameter $parameter): ?TypeStatement
    {
        $type = $parameter->getType();

        if ($type instanceof \ReflectionType) {
            try {
                return $this->getType($type);
            } catch (UnrecognizedReflectionTypeException) {
                throw UnrecognizedReflectionTypeException::fromReflectionParameter($type, $parameter);
            }
        }

        return null;
    }

    /**
     * @throws ReaderExceptionInterface
     * @throws UnrecognizedReflectionTypeException
     * @api
     */
    public function getType(\ReflectionType $type): TypeStatement
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
        /** @var non-empty-string $literal */
        $literal = $type->getName();

        $name = new Name($literal);

        if ($type->isBuiltin() || $name->isSpecial() || $name->isBuiltin()) {
            return new NamedTypeNode($name);
        }

        return new NamedTypeNode(new FullQualifiedName($name));
    }

    /**
     * @throws ReaderExceptionInterface
     * @return UnionTypeNode<TypeStatement>
     */
    private function convertUnionType(\ReflectionUnionType $type): UnionTypeNode
    {
        $children = [];

        foreach ($type->getTypes() as $child) {
            $children[] = $this->getType($child);
        }

        return new UnionTypeNode(...$children);
    }

    /**
     * @throws ReaderExceptionInterface
     * @return IntersectionTypeNode<TypeStatement>
     */
    private function convertIntersectionType(\ReflectionIntersectionType $type): IntersectionTypeNode
    {
        $children = [];

        foreach ($type->getTypes() as $child) {
            $children[] = $this->getType($child);
        }

        return new IntersectionTypeNode(...$children);
    }
}

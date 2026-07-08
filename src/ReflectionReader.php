<?php

declare(strict_types=1);

namespace TypeLang\Reader;

use TypeLang\Reader\Exception\ReaderExceptionInterface;
use TypeLang\Reader\Exception\UnrecognizedConstantTypeException;
use TypeLang\Reader\Exception\UnrecognizedFunctionTypeException;
use TypeLang\Reader\Exception\UnrecognizedParameterTypeException;
use TypeLang\Reader\Exception\UnrecognizedPropertyTypeException;
use TypeLang\Reader\Exception\UnrecognizedReflectionTypeException;
use TypeLang\Reader\Exception\UnrecognizedTypeException;
use TypeLang\Type\IntersectionTypeNode;
use TypeLang\Type\Name;
use TypeLang\Type\NamedTypeNode;
use TypeLang\Type\NullableTypeNode;
use TypeLang\Type\TypeNode;
use TypeLang\Type\UnionTypeNode;

final class ReflectionReader implements ReaderInterface
{
    /**
     * @var list<non-empty-lowercase-string>
     */
    private const array NULLABLE_BUILTIN_TYPES = ['null', 'mixed'];

    public function findConstantType(\ReflectionClassConstant $constant): ?TypeNode
    {
        $type = $constant->getType();

        if ($type instanceof \ReflectionType) {
            try {
                return $this->getType($type);
            } catch (UnrecognizedReflectionTypeException $e) {
                throw UnrecognizedConstantTypeException::becauseConstantTypeIsUnrecognized($type, $constant, $e);
            }
        }

        return null;
    }

    private function findPropertyNativeReadType(\ReflectionProperty $property): ?\ReflectionType
    {
        return $property->getType();
    }

    private function findPropertyNativeWriteType(\ReflectionProperty $property): ?\ReflectionType
    {
        $setter = $property->getHook(\PropertyHookType::Set);

        if ($setter === null) {
            return $this->findPropertyNativeReadType($property);
        }

        foreach ($setter->getParameters() as $parameter) {
            $type = $parameter->getType();

            if ($type instanceof \ReflectionType) {
                return $type;
            }

            break;
        }

        return $this->findPropertyNativeReadType($property);
    }

    public function findPropertyType(
        \ReflectionProperty $property,
        PropertyAccessDirection $access = PropertyAccessDirection::DEFAULT,
    ): ?TypeNode {
        $type = $access === PropertyAccessDirection::Read
            ? $this->findPropertyNativeReadType($property)
            : $this->findPropertyNativeWriteType($property);

        if ($type instanceof \ReflectionType) {
            try {
                return $this->getType($type);
            } catch (UnrecognizedReflectionTypeException $e) {
                throw UnrecognizedPropertyTypeException::becausePropertyTypeIsUnrecognized($type, $property, $e);
            }
        }

        return null;
    }

    public function findFunctionType(\ReflectionFunctionAbstract $function): ?TypeNode
    {
        $type = $function->getReturnType();

        if ($type instanceof \ReflectionType) {
            try {
                return $this->getType($type);
            } catch (UnrecognizedReflectionTypeException $e) {
                throw UnrecognizedFunctionTypeException::becauseFunctionTypeIsUnrecognized($type, $function, $e);
            }
        }

        return null;
    }

    public function findParameterType(\ReflectionParameter $parameter): ?TypeNode
    {
        $type = $parameter->getType();

        if ($type instanceof \ReflectionType) {
            try {
                return $this->getType($type);
            } catch (UnrecognizedReflectionTypeException $e) {
                throw UnrecognizedParameterTypeException::becauseParameterTypeIsUnrecognized($type, $parameter, $e);
            }
        }

        return null;
    }

    /**
     * @api
     *
     * @throws ReaderExceptionInterface
     * @throws UnrecognizedTypeException
     */
    public function getType(\ReflectionType $type): TypeNode
    {
        return match (true) {
            $type instanceof \ReflectionUnionType => $this->convertUnionType($type),
            $type instanceof \ReflectionIntersectionType => $this->convertIntersectionType($type),
            $type instanceof \ReflectionNamedType => $this->convertNamedType($type),
            default => throw UnrecognizedTypeException::becauseTypeIsUnrecognized($type),
        };
    }

    private function convertNamedType(\ReflectionNamedType $type): TypeNode
    {
        $result = $this->convertNonNullNamedType($type);

        if ($type->allowsNull() && !\in_array($type->getName(), self::NULLABLE_BUILTIN_TYPES, true)) {
            return new NullableTypeNode($result);
        }

        return $result;
    }

    private function convertNonNullNamedType(\ReflectionNamedType $type): TypeNode
    {
        /** @var non-empty-string $literal */
        $literal = $type->getName();

        $name = Name::createFromString($literal);

        if ($type->isBuiltin() || $name->isSpecial || $name->isBuiltin) {
            return new NamedTypeNode($name);
        }

        return new NamedTypeNode($name->toFullQualified());
    }

    /**
     * @return UnionTypeNode<TypeNode>
     * @throws ReaderExceptionInterface
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
     * @return IntersectionTypeNode<TypeNode>
     * @throws ReaderExceptionInterface
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

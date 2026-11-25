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
use TypeLang\Type\Literal\BoolLiteralNode;
use TypeLang\Type\Literal\NullLiteralNode;
use TypeLang\Type\LogicalTypeNode\IntersectionTypeNode;
use TypeLang\Type\LogicalTypeNode\UnionTypeNode;
use TypeLang\Type\Name;
use TypeLang\Type\NamedTypeNode;
use TypeLang\Type\TypeNode;
use TypeLang\Type\WrappingTypeNode\NullableTypeNode;

final class ReflectionReader implements ReaderInterface
{
    /**
     * @var list<non-empty-lowercase-string>
     */
    private const NULLABLE_BUILTIN_TYPES = ['null', 'mixed'];

    /**
     * @var list<non-empty-lowercase-string>
     */
    private const SPECIAL_CLASS_NAMES = ['self', 'parent', 'static'];

    /**
     * @var list<non-empty-lowercase-string>
     */
    private const BUILTIN_TYPE_NAMES = [
        'mixed',
        'string',
        'int',
        'float',
        'bool',
        'object',
        'array',
        'void',
        'never',
        'callable',
        'iterable',
        'null',
        'true',
        'false',
    ];

    public function findConstantType(\ReflectionClassConstant $constant): ?TypeNode
    {
        // Constant types exist on PHP 8.3 and later
        if (!\method_exists($constant, 'getType')) {
            return null;
        }

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
        // Property hooks exist on PHP 8.4 and later
        if (!\method_exists($property, 'getHook')) {
            return $this->findPropertyNativeReadType($property);
        }

        /** @var \ReflectionMethod|null */
        $setter = $property->getHook(\constant('PropertyHookType::Set'));

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
     * @throws ReaderExceptionInterface in case of any reading error occurs
     * @throws UnrecognizedTypeException in case of the type is not a union,
     *         an intersection or a named one
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
        /** @var non-empty-string */
        $literal = $type->getName();

        $lowercase = \strtolower($literal);

        // the words the parser reads as literals
        if ($lowercase === 'null') {
            return new NullLiteralNode($literal);
        }

        if ($lowercase === 'true' || $lowercase === 'false') {
            return new BoolLiteralNode($lowercase === 'true', $literal);
        }

        if (
            $type->isBuiltin()
            || \in_array($lowercase, self::SPECIAL_CLASS_NAMES, true)
            || \in_array($lowercase, self::BUILTIN_TYPE_NAMES, true)
        ) {
            return new NamedTypeNode(new Name($literal));
        }

        // A class with no namespace stands right in the root one
        return new NamedTypeNode(Name::from('\\' . $literal));
    }

    /**
     * @return UnionTypeNode<TypeNode>
     * @throws ReaderExceptionInterface in case of any reading error occurs
     */
    private function convertUnionType(\ReflectionUnionType $type): UnionTypeNode
    {
        $children = [];

        foreach ($type->getTypes() as $child) {
            $children[] = $this->getType($child);
        }

        return new UnionTypeNode($children);
    }

    /**
     * @return IntersectionTypeNode<TypeNode>
     * @throws ReaderExceptionInterface in case of any reading error occurs
     */
    private function convertIntersectionType(\ReflectionIntersectionType $type): IntersectionTypeNode
    {
        $children = [];

        foreach ($type->getTypes() as $child) {
            $children[] = $this->getType($child);
        }

        return new IntersectionTypeNode($children);
    }
}

<?php

declare(strict_types=1);

namespace TypeLang\Reader;

use TypeLang\Parser\Node\Stmt\TypeStatement;
use TypeLang\Parser\Parser;
use TypeLang\Parser\ParserInterface;
use TypeLang\Reader\AttributeReader\AttributeProviderInterface;
use TypeLang\Reader\AttributeReader\DefaultAttributeProvider;
use TypeLang\Reader\Exception\TypeReadingException;

final class AttributeReader implements ReaderInterface
{
    /**
     * @param AttributeProviderInterface<object> $provider
     */
    public function __construct(
        private readonly ParserInterface $parser = new Parser(),
        // @phpstan-ignore-next-line
        private readonly AttributeProviderInterface $provider = new DefaultAttributeProvider(),
    ) {}

    /**
     * @param \ReflectionProperty|\ReflectionParameter|\ReflectionFunctionAbstract|\ReflectionClassConstant $reflector
     *
     * @throws TypeReadingException
     */
    private function tryRead(\Reflector $reflector): ?TypeStatement
    {
        $attributes = $reflector->getAttributes($this->provider->getAttribute(), \ReflectionAttribute::IS_INSTANCEOF);

        foreach ($attributes as $attribute) {
            $instance = $attribute->newInstance();

            $definition = $this->provider->getTypeFromAttribute($instance);

            try {
                $statement = $this->parser->parse($definition);
            } catch (\Throwable $e) {
                throw TypeReadingException::fromInternalError($e);
            }

            return $statement;
        }

        return null;
    }

    public function findPropertyType(\ReflectionProperty $property): ?TypeStatement
    {
        return $this->tryRead($property);
    }

    public function findConstantType(\ReflectionClassConstant $constant): ?TypeStatement
    {
        return $this->tryRead($constant);
    }

    public function findFunctionType(\ReflectionFunctionAbstract $function): ?TypeStatement
    {
        return $this->tryRead($function);
    }

    public function findParameterType(\ReflectionParameter $parameter): ?TypeStatement
    {
        return $this->tryRead($parameter);
    }
}

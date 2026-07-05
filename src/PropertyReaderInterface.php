<?php

declare(strict_types=1);

namespace TypeLang\Reader;

use TypeLang\Reader\Exception\ReaderExceptionInterface;
use TypeLang\Type\TypeNode;

interface PropertyReaderInterface
{
    /**
     * Returns a type AST structure based on an {@see ReflectionProperty} object.
     *
     * @throws ReaderExceptionInterface in case of any reading error occurs
     */
    public function findPropertyType(
        \ReflectionProperty $property,
        PropertyAccessDirection $access = PropertyAccessDirection::DEFAULT,
    ): ?TypeNode;
}

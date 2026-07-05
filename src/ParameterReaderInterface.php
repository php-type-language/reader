<?php

declare(strict_types=1);

namespace TypeLang\Reader;

use TypeLang\Reader\Exception\ReaderExceptionInterface;
use TypeLang\Type\TypeNode;

interface ParameterReaderInterface
{
    /**
     * Returns a type AST structure based on an {@see ReflectionParameter} object.
     *
     * @throws ReaderExceptionInterface in case of any reading error occurs
     */
    public function findParameterType(\ReflectionParameter $parameter): ?TypeNode;
}

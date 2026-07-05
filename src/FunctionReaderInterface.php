<?php

declare(strict_types=1);

namespace TypeLang\Reader;

use TypeLang\Reader\Exception\ReaderExceptionInterface;
use TypeLang\Type\TypeNode;

interface FunctionReaderInterface
{
    /**
     * Returns a type AST structure based on an {@see ReflectionFunctionAbstract} object.
     *
     * @throws ReaderExceptionInterface in case of any reading error occurs
     */
    public function findFunctionType(\ReflectionFunctionAbstract $function): ?TypeNode;
}

<?php

declare(strict_types=1);

namespace TypeLang\Reader;

use TypeLang\Parser\Node\Stmt\TypeStatement;
use TypeLang\Reader\Exception\ReaderExceptionInterface;

interface FunctionReaderInterface
{
    /**
     * Returns a type AST structure based on an {@see ReflectionFunctionAbstract} object.
     *
     * @throws ReaderExceptionInterface in case of any reading error occurs
     */
    public function findFunctionType(\ReflectionFunctionAbstract $function): ?TypeStatement;
}

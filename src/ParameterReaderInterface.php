<?php

declare(strict_types=1);

namespace TypeLang\Reader;

use TypeLang\Parser\Node\Stmt\TypeStatement;
use TypeLang\Reader\Exception\ReaderExceptionInterface;

interface ParameterReaderInterface
{
    /**
     * Returns a type AST structure based on an {@see ReflectionParameter} object.
     *
     * @throws ReaderExceptionInterface in case of any reading error occurs
     */
    public function findParameterType(\ReflectionParameter $parameter): ?TypeStatement;
}

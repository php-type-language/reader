<?php

declare(strict_types=1);

namespace TypeLang\Reader;

use TypeLang\Parser\Node\Stmt\TypeStatement;
use TypeLang\Reader\Exception\ReaderExceptionInterface;

interface ConstantReaderInterface
{
    /**
     * Returns a type AST structure based on an {@see ReflectionClassConstant} object.
     *
     * @throws ReaderExceptionInterface In case of any reading error occurs.
     */
    public function findConstantType(\ReflectionClassConstant $constant): ?TypeStatement;
}

<?php

declare(strict_types=1);

namespace TypeLang\Reader;

use TypeLang\Reader\Exception\ReaderExceptionInterface;
use TypeLang\Type\TypeNode;

interface ConstantReaderInterface
{
    /**
     * Returns a type AST structure based on an
     * {@see \ReflectionClassConstant} object, or {@see null} in case of the
     * constant declares no type or PHP does not support constant types.
     *
     * @throws ReaderExceptionInterface in case of any reading error occurs
     */
    public function findConstantType(\ReflectionClassConstant $constant): ?TypeNode;
}

<?php

declare(strict_types=1);

namespace TypeLang\Reader;

use TypeLang\Parser\Node\Stmt\TypeStatement;
use TypeLang\Reader\Exception\ReaderExceptionInterface;

interface ReaderInterface
{
    /**
     * Returns a type AST structure based on an {@see ReflectionProperty} object.
     *
     * @throws ReaderExceptionInterface In case of any reading error occurs.
     */
    public function findPropertyType(\ReflectionProperty $property): ?TypeStatement;

    /**
     * Returns a type AST structure based on an {@see ReflectionFunctionAbstract} object.
     *
     * @throws ReaderExceptionInterface In case of any reading error occurs.
     */
    public function findFunctionType(\ReflectionFunctionAbstract $function): ?TypeStatement;

    /**
     * Returns a type AST structure based on an {@see ReflectionParameter} object.
     *
     * @throws ReaderExceptionInterface In case of any reading error occurs.
     */
    public function findParameterType(\ReflectionParameter $parameter): ?TypeStatement;

    /**
     * Returns a type AST structure based on an {@see ReflectionClassConstant} object.
     *
     * @throws ReaderExceptionInterface In case of any reading error occurs.
     */
    public function findConstantType(\ReflectionClassConstant $constant): ?TypeStatement;
}
<?php

declare(strict_types=1);

namespace TypeLang\Reader\AttributeReader;

use TypeLang\Parser\Node\Stmt\TypeStatement;

/**
 * @template T of object
 */
interface AttributeProviderInterface
{
    /**
     * @return class-string<T>
     */
    public function getAttribute(): string;

    /**
     * @param T $attribute
     */
    public function getTypeFromAttribute(object $attribute): string;

    /**
     * @param T $attribute
     */
    public function process(object $attribute, TypeStatement $statement): TypeStatement;
}

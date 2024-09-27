<?php

declare(strict_types=1);

namespace TypeLang\Reader\AttributeReader;

use TypeLang\Parser\Node\Stmt\TypeStatement;

/**
 * @template T of object
 * @template-implements AttributeProviderInterface<T>
 */
abstract class AttributeProvider implements AttributeProviderInterface
{
    public function process(object $attribute, TypeStatement $statement): TypeStatement
    {
        return $statement;
    }
}

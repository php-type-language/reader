<?php

declare(strict_types=1);

namespace TypeLang\Reader\AttributeReader;

use TypeLang\Reader\Attribute\MapType;

/**
 * @template-extends AttributeProvider<MapType>
 */
final class DefaultAttributeProvider extends AttributeProvider
{
    public function getAttribute(): string
    {
        return MapType::class;
    }

    public function getTypeFromAttribute(object $attribute): string
    {
        return $attribute->type;
    }
}

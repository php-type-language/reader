<?php

declare(strict_types=1);

namespace TypeLang\Reader\Attribute;

// If you write it like this, PHPStorm will screw up.
// #[\Attribute(\Attribute::TARGET_ALL & ~\Attribute::TARGET_CLASS)]
// I believe that someday it will begin to understand PHP code...
#[\Attribute(\Attribute::TARGET_FUNCTION
    | \Attribute::TARGET_METHOD
    | \Attribute::TARGET_PROPERTY
    | \Attribute::TARGET_CLASS_CONSTANT
    | \Attribute::TARGET_PARAMETER)]
class MapType
{
    /**
     * @param non-empty-string $type
     */
    public function __construct(
        public readonly string $type,
    ) {}
}

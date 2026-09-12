<?php

declare(strict_types=1);

namespace TypeLang\Reader;

enum PropertyAccessDirection
{
    case Read;
    case Write;

    public const DEFAULT = self::Read;
}

<?php

declare(strict_types=1);

namespace TypeLang\ReflectionConverter;

use TypeLang\Parser\Node\Stmt\TypeStatement;
use TypeLang\ReflectionConverter\Exception\ConverterExceptionInterface;

interface ConverterInterface
{
    /**
     * @throws ConverterExceptionInterface In case of any convert error occurs.
     */
    public function convert(\ReflectionType $type): TypeStatement;
}

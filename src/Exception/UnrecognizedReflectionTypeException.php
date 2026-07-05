<?php

declare(strict_types=1);

namespace TypeLang\Reader\Exception;

/**
 * Occurs when a reflection type cannot be recognized and converted into a
 * type node.
 */
abstract class UnrecognizedReflectionTypeException extends TypeReadingException {}

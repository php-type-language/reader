<?php

declare(strict_types=1);

namespace TypeLang\Reader\Exception;

/**
 * Occurs when a reflection type cannot be recognized and converted into a
 * type node.
 */
abstract class UnrecognizedReflectionTypeException extends ReaderException
{
    final public function __construct(
        /**
         * The reflection type that is not recognized
         */
        public readonly \ReflectionType $type,
        /**
         * The element the type is declared on: a property, a parameter, a
         * function or a constant, {@see null} in case of the type is read
         * alone
         */
        public readonly ?\Reflector $reflector,
        string $message,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, 0, $previous);
    }

    /**
     * Returns the name of the function or of the method with its class:
     * `App\User::getName`.
     */
    protected static function formatFunction(\ReflectionFunctionAbstract $function): string
    {
        if ($function instanceof \ReflectionMethod) {
            return $function->class . '::' . $function->name;
        }

        return $function->name;
    }
}

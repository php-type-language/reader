<?php

declare(strict_types=1);

namespace TypeLang\ReflectionConverter\Tests\Functional;

use PHPUnit\Framework\Attributes\Group;
use TypeLang\ReflectionConverter\Tests\TestCase as BaseTestCase;

#[Group('functional'), Group('type-lang/reflection-converter')]
abstract class TestCase extends BaseTestCase {}

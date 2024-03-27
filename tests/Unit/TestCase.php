<?php

declare(strict_types=1);

namespace TypeLang\ReflectionConverter\Tests\Unit;

use PHPUnit\Framework\Attributes\Group;
use TypeLang\ReflectionConverter\Tests\TestCase as BaseTestCase;

#[Group('unit'), Group('type-lang/reflection-converter')]
abstract class TestCase extends BaseTestCase {}

<?php

declare(strict_types=1);

namespace TypeLang\Reader\Tests\Functional;

use PHPUnit\Framework\Attributes\Group;
use TypeLang\Reader\Tests\TestCase as BaseTestCase;

#[Group('functional'), Group('type-lang/reader')]
abstract class TestCase extends BaseTestCase {}

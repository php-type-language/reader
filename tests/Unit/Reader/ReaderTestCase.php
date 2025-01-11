<?php

declare(strict_types=1);

namespace TypeLang\Reader\Tests\Unit\Reader;

use Composer\InstalledVersions;
use PHPUnit\Framework\Attributes\Group;
use TypeLang\Parser\Node\Node;
use TypeLang\Parser\Node\Stmt\TypeStatement;
use TypeLang\Parser\Traverser;
use TypeLang\Parser\Traverser\Command;
use TypeLang\Reader\AttributeReader;
use TypeLang\Reader\ReflectionReader;
use TypeLang\Reader\Tests\Unit\TestCase;

#[Group('unit'), Group('type-lang/reader')]
abstract class ReaderTestCase extends TestCase
{
    protected static function normalize(TypeStatement $stmt): TypeStatement
    {
        Traverser::through(new class extends Traverser\Visitor {
            public function enter(Node $node): ?Command
            {
                $node->offset = 0;
                return null;
            }
        }, [$stmt]);

        return $stmt;
    }

    protected static function assertSameType(?TypeStatement $expected, ?TypeStatement $actual, string $message = ''): void
    {
        if ($actual !== null) {
            $actual = self::normalize($actual);
        }

        self::assertEquals($expected, $actual, $message);
    }

    public static function readersDataProvider(): iterable
    {
        yield ReflectionReader::class => [new ReflectionReader()];

        $parserVersion = InstalledVersions::getVersion('type-lang/parser')
            ?? '0.0.0.0';

        // @see https://github.com/php-type-language/parser/releases/tag/1.2.1
        if (\version_compare($parserVersion, '1.2.1', '>=')) {
            yield AttributeReader::class => [new AttributeReader()];
        }
    }
}

<a href="https://github.com/php-type-language" target="_blank">
    <img align="center" src="https://github.com/php-type-language/.github/blob/master/assets/dark.png?raw=true">
</a>

<p align="center">
    <a href="https://packagist.org/packages/type-lang/reader"><img src="https://poser.pugx.org/type-lang/reader/require/php?style=for-the-badge" alt="PHP 8.1+"></a>
    <a href="https://packagist.org/packages/type-lang/reader"><img src="https://poser.pugx.org/type-lang/reader/version?style=for-the-badge" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/type-lang/reader"><img src="https://poser.pugx.org/type-lang/reader/v/unstable?style=for-the-badge" alt="Latest Unstable Version"></a>
    <a href="https://raw.githubusercontent.com/php-type-language/reader/blob/master/LICENSE"><img src="https://poser.pugx.org/type-lang/reader/license?style=for-the-badge" alt="License MIT"></a>
</p>
<p align="center">
    <a href="https://github.com/php-type-language/reader/actions"><img src="https://github.com/php-type-language/reader/workflows/tests/badge.svg"></a>
</p>

---

Converts native PHP types exposed by Reflection objects into **TypeLang**
`TypeLang\Type\*` AST nodes.

Given a `ReflectionClassConstant`, `ReflectionProperty`, `ReflectionParameter`
or `ReflectionFunctionAbstract`, it returns the matching type node (or `null`
when no type is declared).

Full documentation is available at [typelang.dev](https://typelang.dev).

## Installation

Install the package via [Composer](https://getcomposer.org):

```sh
composer require type-lang/reader
```

**Requirements:** 
- PHP 8.4+

## Usage

`TypeLang\Reader\ReflectionReader` exposes a `find*Type()` method for every kind
of Reflection object:

```php
$reader = new TypeLang\Reader\ReflectionReader();

$node = $reader->findFunctionType(
    new ReflectionFunction(function (): void {}),
);

var_dump($node);
// object(TypeLang\Type\NamedTypeNode) {
//   ["name"] => "void"
//   ...
// }
```

Combine it with [`type-lang/printer`](https://packagist.org/packages/type-lang/printer)
to dump the resolved types of a whole class:

```php
$class = new ReflectionClass(Path\To\Example::class);
$reader = new TypeLang\Reader\ReflectionReader();
$printer = new TypeLang\Printer\PrettyTypePrinter();

foreach ($class->getProperties() as $property) {
    if ($type = $reader->findPropertyType($property)) {
        echo "property {$property->name}: {$printer->print($type)}\n";
    }
}

foreach ($class->getMethods() as $method) {
    if ($type = $reader->findFunctionType($method)) {
        echo "method {$method->name}(): {$printer->print($type)}\n";
    }
}
```

Constants (`findConstantType()`) and parameters (`findParameterType()`) are read
the same way.

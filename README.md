<a href="https://github.com/php-type-language" target="_blank">
    <img align="center" src="https://github.com/php-type-language/.github/blob/master/assets/dark.png?raw=true">
</a>

---

<p align="center">
    <a href="https://packagist.org/packages/type-lang/reader"><img src="https://poser.pugx.org/type-lang/reader/require/php?style=for-the-badge" alt="PHP 8.1+"></a>
    <a href="https://packagist.org/packages/type-lang/reader"><img src="https://poser.pugx.org/type-lang/reader/version?style=for-the-badge" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/type-lang/reader"><img src="https://poser.pugx.org/type-lang/reader/v/unstable?style=for-the-badge" alt="Latest Unstable Version"></a>
    <a href="https://raw.githubusercontent.com/php-type-language/reader/blob/master/LICENSE"><img src="https://poser.pugx.org/type-lang/reader/license?style=for-the-badge" alt="License MIT"></a>
</p>
<p align="center">
    <a href="https://github.com/php-type-language/reader/actions"><img src="https://github.com/php-type-language/reader/workflows/tests/badge.svg"></a>
</p>

Provides a set of methods for converting PHP Reflection objects into the
TypeLang AST Nodes.

Read [documentation pages](https://typelang.dev) for more information.

## Installation

TypeLang Reader is available as Composer repository and can 
be installed using the following command in a root of your project:

```sh
composer require type-lang/reader
```

## Quick Start

```php
$reader = new \TypeLang\Reader\ReflectionReader();

$node = $reader->findFunctionType(
    function: new \ReflectionFunction(function(): void {}),
);

var_dump($node);
```

**Expected Output:**
```
TypeLang\Parser\Node\Stmt\NamedTypeNode {
  +offset: 0
  +name: TypeLang\Parser\Node\Name {
    +offset: 0
    -parts: array:1 [
      0 => TypeLang\Parser\Node\Identifier {
        +offset: 0
        +value: "void"
      }
    ]
  }
  +arguments: null
  +fields: null
}
```

### Creating From Reflection

```php
$class = new \ReflectionClass(Path\To\Example::class);

// Printer component provided by "type-lang/printer" package.
$printer = new \TypeLang\Printer\PrettyPrinter();

$converter = new \TypeLang\Reader\ReflectionReader();

// Dump all constants with its types.
foreach ($class->getReflectionConstants() as $constant) {
    // Creates type node AST from a constant's type.
    if ($type = $converter->findConstantType($constant)) {
        echo 'const ' . $constant->name . ' has type ' . $printer->print($type) . "\n";
    }
}

// Dump all properties with its types.
foreach ($class->getProperties() as $property) {
    // Creates type node AST from a property's type.
    if ($type = $converter->findPropertyType($property)) {
        echo 'property ' . $property->name . ' has type ' . $printer->print($type) . "\n";
    }
}

// Dump all methods with its types.
foreach ($class->getMethods() as $method) {
    // Creates type node AST from any function's return type.
    if ($type = $converter->findFunctionType($method)) {
        echo 'function ' . $method->name . ' has type ' . $printer->print($type) . "\n";
    }
    
    // Creates type node AST from a parameter's type.
    foreach ($method->getParameters() as $parameter) {
        if ($type = $converter->findParameterType($parameter)) {
            echo 'parameter ' . $parameter->name . ' has type ' . $printer->print($type) . "\n";
        }
    }
}
```

<a href="https://github.com/php-type-language" target="_blank">
    <picture>
        <img align="center" src="https://github.com/php-type-language/.github/blob/master/assets/dark.png?raw=true">
    </picture>
</a>

---

<p align="center">
    <a href="https://packagist.org/packages/type-lang/reflection-converter"><img src="https://poser.pugx.org/type-lang/reflection-converter/require/php?style=for-the-badge" alt="PHP 8.1+"></a>
    <a href="https://packagist.org/packages/type-lang/reflection-converter"><img src="https://poser.pugx.org/type-lang/reflection-converter/version?style=for-the-badge" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/type-lang/reflection-converter"><img src="https://poser.pugx.org/type-lang/reflection-converter/v/unstable?style=for-the-badge" alt="Latest Unstable Version"></a>
    <a href="https://raw.githubusercontent.com/php-type-language/reflection-converter/blob/master/LICENSE"><img src="https://poser.pugx.org/type-lang/reflection-converter/license?style=for-the-badge" alt="License MIT"></a>
</p>
<p align="center">
    <a href="https://github.com/php-type-language/reflection-converter/actions"><img src="https://github.com/php-type-language/reflection-converter/workflows/tests/badge.svg"></a>
</p>

Provides a set of methods for converting PHP reflection objects into the 
Type Language AST nodes.

Read [documentation pages](https://phpdoc.io) for more information.

## Installation

Type Language Reflection Converter is available as Composer repository and can 
be installed using the following command in a root of your project:

```sh
$ composer require type-lang/reflection-converter
```

## Quick Start

```php
// Type contains ReflectionNamedType{ name: "void" }
$function = new \ReflectionFunction(function(): void {});
$type = $function->getReturnType();

$converter = new \TypeLang\ReflectionConverter\Converter();
$node = $converter->convert($type);

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

$converter = new \TypeLang\ReflectionConverter\Converter();

// Dump all constants with its types.
foreach ($class->getReflectionConstants() as $constant) {
    // Creates type node AST from a constant's type.
    if ($type = $converter->convertConstantType($constant)); {
        echo 'const ' . $constant->name . ' has type ' . $printer->print($type) . "\n";
    }
}

// Dump all properties with its types.
foreach ($class->getProperties() as $property) {
    // Creates type node AST from a property's type.
    if ($type = $converter->convertPropertyType($property)); {
        echo 'property ' . $property->name . ' has type ' . $printer->print($type) . "\n";
    }
}

// Dump all methods with its types.
foreach ($class->getMethods() as $method) {
    // Creates type node AST from any function's return type.
    if ($type = $converter->convertFunctionType($method)); {
        echo 'function ' . $method->name . ' has type ' . $printer->print($type) . "\n";
    }
    
    // Creates type node AST from a parameter's type.
    foreach ($method->getParameters() as $parameter) {
        if ($type = $converter->convertParameterType($parameter)); {
            echo 'parameter ' . $parameter->name . ' has type ' . $printer->print($type) . "\n";
        }
    }
}
```

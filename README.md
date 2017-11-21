# Axessors

[![Build Status](https://travis-ci.org/NoOne4rever/Axessors.svg?branch=master)](https://travis-ci.org/NoOne4rever/Axessors)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/NoOne4rever/Axessors/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/NoOne4rever/Axessors/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/NoOne4rever/Axessors/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/NoOne4rever/Axessors/?branch=master)
[![Software License]](https://img.shields.io/badge/license-GPL-blue.svg?style=flat)
Generator of getters and setters for PHP.

## Installation

`composer require "noone4rever/axessors @dev"`

You can see this package on [packagist](https://packagist.org/packages/noone4rever/axessors).

## System requirements

You need to use PHP 7.1 or newer to run a code with **Axessors**.

## Problem

When you write your code in object-oriented style, you need to provide almost every class with getters and setters for `private` fields. For example, you have a class named `Email`. It stores email address and can parse it, e.g. to get an email domain.
```php
class Email
{
    public $email;
    
    public function __construct()
    {
        /* ... */
    }
    
    public function getEmailDomain()
    {
        /* ... */
    }
}
```
Field `$email` is `public`, and user's code can put there any data, so your code probably won't work correctly.
```php
$emailAddress->email = new \stdclass();
```
We can add methods `getEmail()` and `setEmail()` to the class to restrict possible range of data, that can be stored in the field `$email`.
```php
public function setEmail(string $val): void
{
    $this->email = $val;
}

public function getEmail(): string
{
    return $this->email;
}
```
The more fields we have in our class, the more getters and setters we have to write. If our class have five fields, it will have ten or less accessors. Getters and setters always have the same structure (getter returns field value, setter rewrites field value), so accessors belong to one of the sorts code duplication.

To avoid writing getters and setters manually we can:
* make all the fields `public` (it is not safe enough)
* generate accessors with IDE (a class will still have a lot of duplicated code)
* use *properties* (this can help not to write getters and setters without any additional logic only)
* use special libraries for generating accessors (in fact there are almost no such libraries for PHP, that can help you to write less code)

**Axessors** introduce a short syntax to describe getters and setters.

## Structure of getters and setters

We can extend setter for the field `$email`.
```php
public function setEmail(string $val): void
{
    if (preg_match('/[a-z][a-z\d_\.]*@[a-z]+\.[a-z]+/i', $email)) {
        $this->email = strtolower($val);
    } else {
        throw new ValidationException('not a valid email address given');
    }
}
```
In this case setter:
* runs conditions checkout
  * checks type compatibility
  * ensures, that given string matches a regex
* runs some callbacks
  * casts a string to lowercase
* rewrites field value

Any accessor with additional logic has the same structure, excluding rewriting of the field - getter returns field value.
The same structure of accessors allows me to make a short syntax for getters and setters.

## Usage

To start using **Axessors**, `use` *Axessors* trait in your class.
```php
use NoOne4rever\Axessors\Axessors;

class WithTrait
{
    use Axessors;

    /* ... */
}
```
Fields, that should have accessors ought to be commented with a special *Axessors comment*. The library will use your comments to generate accessors.
```php
private $field; #> +axs mixed
```

## File structure

To use **Axessors** you should follow this file structure:
* include of `/vendor/autoload.php`
* declaration of all your classes
* call of AxessorsStartup::run()

```php
include '/vendor/autoload.php';

include '/MyClassA.php';
include '/MyClassB.php';
include '/MyClassC.php';

AxessorsStartup::run();
```

## Axessors comments

*Axessors comment* has simple syntax. It starts with `#>` and contains:
* special keywords
* access modifiers
* data types declarations
* conditions
* callbacks
* field aliases

### Keywords

*Axessors comments* should contain special keywords. Keywords are used to generate accessors. Write `readable` or `rdb` for getter, `writable` or `wrt` for setter and `accessible` or `axs` for both methods.
```php
class WithKeywords
{
    use Axessors;

    private $a; #> +rdb int
    private $b; #> +wrt int
    private $c; #> +axs int
}

$test = new WithKeywords();
$test->getA();
$test->setB(1);
$test->getC();
$test->setC(1);
```
Generated methods have automatically formed names: `get<Field>` and `set<Field>`.

### Access modifiers

Access modifiers should be written before the keywords. To shorten syntax `public`, `protected` and `private` are replaced with `+`, `~` and `-` symbols.
```php
class WithAccessModifiers
{
    use Axessors;

    private $publicAccess; #> +axs int
    private $protectedAccess; #> ~axs int
    private $privateAccess; #> -axs int
}

$test = new WithAccessModifiers();
$test->getPublicAccess(); // OK
$test->getPrivateAccess(); // Error
```
We can define different access modifiers for getter and setter:
```php
private $field = 'smth'; #> ~wrt +rdb
```

### Type declarations

Type declaration should be placed after the keyword. **Axessors** support standard PHP types: `int`, `float`, `bool`, `string`, `array`, `object`, `resource`and `callable`. Type can also be described with class' name or `mixed`.
```php
class WithTypeDeclarations
{
    use Axessors;

    private $email; #> +axs string
}
```
It is possible to write several types, that are separated by `|`.
```php
class WithMultipleTypeDeclarations
{
    use Axessors;

    private $bigNumber; #> +axs int|string
}
```
In this case a number can be represented in scientific form (like "1e15") and then can be parsed in some way.

If the field has default value, you don't have to declare its type manually.
```php
class WithDefaultFieldValue
{
    use Axessors;

    private $epsilon = 1e-9; #> +rdb
}
```
If the field has array-compatible (iterateable) type, you can specify array's content. For example, array of strings is written as `array[string]`. Iterateable type might have any depth, e.g. array of array of integer is written as `array[array[integer]]`. Arrays can contain elements with different types: `array[int|string]`.
```php
class Config
{
    private static $settings; #> +axs array[int|string]
}
```
**Axessors** also support *extended* types, that have additional methods. *Extended* types have capitalized names. See [axessors methods](https://github.com/NoOne4rever/Axessors#axessors-methods).

### Conditions

Sometimes we need to make a short condition for getter or setter. For example, field `$age` can contain any integer in range [1..120]. To perform a checkout of integer, we will write something like this:
```php
class WithConditionalSetter
{
    private $age;

    public function setAge(int $val): void
    {
        if ($val >= 1 && $val <= 120) {
            $this->age = $val;
        } else {
            throw new ValidationException('given age is not possible');
        }
    }
}
```
**Axessors** can perform such checkouts too. The library support mathematical expressions for `int`, `float`, `array` and `string`. Strings are represented as `strlen($var)`, arrays are represented as `count($var)`.

You can write in *Axessors comment* next operators:
* range, e.g. `1..10`
* `<`, `>`
* `<=`, `>=`, `==`
* `%` (means divisibility of number)

The last example will look like this, using the library:
```php
class WithConditionalSetter
{
    use Axessors;

    private $age; #> +wrt int 1..120
}
```
**Axessors** support *injected* conditions too. Such expressions are written between the backquotes. For example, we need to check if the new value of field matches regex. This code will perform this checkout.
```php
class Email
{
    use Axessors;

    private $email; #> +axs string `preg_match('/[a-z][a-z\d_\.]*@[a-z]+\.[a-z]+/i', $var)`
}
```
In this case `$var` means argument for setter. `$var` is reserved identifier, it always contain setter's argument (if we write conditions for `writable` statement) or actual value of field (if we write conditions for `readable` statement).

**Axessors** support multiple conditions. You can group your conditional expressions using `&&` and `||` signs. `||` has higher priority than `&&`.
```php
class Email
{
    use Axessors;

    private $email; #> +axs string < 120 && `preg_match('/[a-z][a-z\d_\.]*@[a-z]+\.[a-z]+/i', $var)`
}
```
Here we can ensure, that `$email` will contain a string with length less than 120 symbols and this string will match our regex.

### Callbacks

**Axessors** support short callbacks in getters and setters. Callback expressions are written after conditions and callback sign: `>>`.

Most of standard types have their own predefined callbacks:
* string
  * lower
  * upper
  * reverse
* int, float
  * inc
  * dec
* array
  * flip
  * shuffle
* bool
  * inverse

We can use `lower` to cast email address to lowercase.
```php
class Email
{
    use Axessors;

    private $email; #> +axs string `preg_match('/[a-z][a-z\d_\.]*@[a-z]+\.[a-z]+/i', $var)` >> lower
}
```
**Axessors** support *injected* callbacks too.
```php
class WithInjectedCallback
{
    use Axessors;

    private $system; #> +axs string <= 100 >> `system('explorer %APPDATA%')`
}
```
You can write in the *injected* callback anything you want. In the last example setter will open folder with applications data on Windows. `$var` can be modified in the *injected* callback too: ``$var += 16``.

You can write several callbacks separated by commas.
```php
class WithNumber
{
    use Axessors;

    private $number; #> +axs int >> inc, dec
}
```
#### Resolving class names

If you use relative class names in the *injected* callback or condition, write `:` before the class' name to use current namespace.
```php
class WithRelativeNames
{
    use Axessors;
    
    private $field; #> +axs `:CurrentNamespaceClass::doSmth()` >> `globalNamespaceClass::doSmthElse()`
}
```
**Axessors** recognize relative class names as absolute. It is not a bug, just a feature, but maybe such behavior will be removed in next versions of library.
 
### Fields' aliases

You can choose, which name of the field will be used in getter or setter signature. For example, you have a field with really long name:
```php
class WithLongField
{
    private $thisFieldWithReallyLooooongName;
}
```
So, default name for getter will be `getThisFieldWithReallyLooooongName`. We can fix this using field alias.

Field alias is written after callbacks section and alias sign: `=>`.
```php
class WithLongField
{
    private $thisFieldWithReallyLooooongName; #> +rdb mixed => shortName
}
```
Now getter name is `getShortName`.

This feature can help you to avoid collisions between child and parent class methods:
```php
class ParentClass
{
    use Axessors;

    private $field; #> +axs int => parentField
}

class ChildClass extends ParentClass
{
    use Axessors;

    private $field; #> +axs int => childField
}
```
So, automatically generated methods won't collide.

## Axessors methods

**Axessors** can generate not only getters and setters. The library can emulate different standard methods, that are defined in extended library types. For example, extended `array` type has methods `add<Field>`, `delete<Field>` and `count<Field>`.

For example, we have an indexed array with strings.
```php
class WithArrayOfStrings
{
    use Axessors;

    private $strings; #> +axs Array[string]
}
```
Now our class have methods `addStrings()`, `deleteStrings()` - this methods take index as argument - and `countStrings()`.

## Implementing interfaces

**Axessors** support implementation of interfaces. You can comment a method in interface or abstract class in **UNIX** style, - **Axessors** will check class hierarchy and stop the program if there are not implemented methods.
```php
interface Locatable
{
    # public function getX(): int;
    # public function getY(): int;
    # public function setX(int $val): void;
    # public function setY(int $val): void;
}

abstract class Shape implements Locatable
{
    use Axs;

    # abstract public fucntion getId(): int;
}

class Triangle extends Shape
{
    use Axessors;

    private $id; #> +rdb int
    private $x; #> +axs int
    private $y; #> +axs int
}
```
Abstract classes with abstract *Axessors* methods should `use` trait *Axs*.

## Integration with IDE

Actually, **Axessors** aren't integrated with any IDE. Unfortunately, automatically generated methods will be marked as non-existing.

You can solve this problem by disabling such inspection in your IDE settings or by writing PHPdoc comments before class definition:
```php
/**
 * Class with Axessors methods.
 * 
 * @method int getInstanceField() getter for instace field
 * @methdd static int getClassField() getter for class field
 */
class WithAxessorsMethods
{
    use Axessors;
    
    private static $classField; #> +axs int
    
    private $instanceField; #> +axs int
}
```
At an nearly date I will create a plugin for PHPStorm, that will provide this IDE with full integration with the library.

## Conclusions

With **Axessors** you can shorten description of every getter and setter in your code. The most complex *Axessors comment* have this structure:
1. `#>`
2. setter access modifier
3. `wrt` or `writable`
4. type declaration
5. conditions for input value
6. `>>`
7. callbacks for input value
8. getter access modifier
9. `rdb` or `readable`
10. conditions for field value
11. `>>`
12. callbacks for field value
13. `=>`
14. field alias

This comment structure can implement almost all the possible getters and setters in one line, and the library can halve your code by removing duplicated methods.

You can see examples in the [special directory](https://github.com/NoOne4rever/Axessors/tree/master/examples).

##### Ask me any questions about the library, and I will be glad to answer.
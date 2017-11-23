<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @package NoOne4rever\Axessors
 * @license GPL
 */

namespace NoOne4rever\Axessors\Examples;

use NoOne4rever\Axessors\{
    Axessors,
    AxessorsStartup,
    Exceptions\OopError
};

require __DIR__ . '/../../vendor/autoload.php';

/**
 * Sample for Axessors access modifiers.
 *
 * @method mixed getPublicAccess() getter for AccessModifiersSample::$publicAccess
 * @method mixed getProtectedAccess() getter for AccessModifiersSample::$protectedAccess
 * @method mixed getPrivateAccess() getter for AccessModifiersSample::$privateAccess
 * @method void setPublicAccess(mixed $val) setter for AccessModifiersSample::$publicAccess
 * @method void setProtectedAccess(mixed $val) setter for AccessModifiersSample::$protectedAccess
 * @method void setPrivateAccess(mixed $val) setter for AccessModifiersSample::$privateAccess
 */
class AccessModifiersSample
{
    use Axessors;

    /** @var mixed a field with public accessors */
    private $publicAccess; #: +axs mixed
    /** @var mixed a field with protected accessors */
    private $protectedAccess; #: ~axs mixed
    /** @var mixed a field with private accessors */
    private $privateAccess; #: -axs mixed

    /** Tests a method with public access. */
    public function testPublic(): void
    {
        $this->setPublicAccess('public:test');
        echo $this->getPublicAccess();
    }

    /** Tests a method with protected access. */
    public function testProtected(): void
    {
        $this->setProtectedAccess('protected:test');
        echo $this->getProtectedAccess();
    }

    /** Tests a method with private access. */
    public function testPrivate(): void
    {
        $this->setPrivateAccess('private:test');
        echo $this->getPrivateAccess();
    }

    /** Tests method call from instance. */
    public function testInstance(): void
    {
        $sample = new self();
        $sample->setPrivateAccess('private:instance');
        echo $sample->getPrivateAccess();
    }
}

/** Class ChildClass. */
class ChildAccessModifiers extends AccessModifiersSample
{
    use Axessors;

    /** Tests a method with public access. */
    public function testPublic(): void
    {
        $this->setPublicAccess('public:child');
        echo $this->getPublicAccess();
    }

    /** Tests a method with protected access. */
    public function testProtected(): void
    {
        $this->setProtectedAccess('protected:child');
        echo $this->getProtectedAccess();
    }

    /** Tests a method with private access. */
    public function testPrivate(): void
    {
        $this->setPrivateAccess('private:child');
        echo $this->getPrivateAccess();
    }

    /** Tests method call from instance. */
    public function testInstance(): void
    {
        $sample = new parent();
        $sample->setProtectedAccess('protected:childInstance');
        echo $sample->getProtectedAccess();
    }
}

AxessorsStartup::run();

// Testing sample class itself:

$sample = new AccessModifiersSample();

$sample->testPublic();
echo PHP_EOL;

$sample->testProtected();
echo PHP_EOL;

$sample->testPrivate();
echo PHP_EOL;

// Testing child:

$child = new ChildAccessModifiers();

$child->testPublic();
echo PHP_EOL;

$child->testProtected();
echo PHP_EOL;

try {
    $child->testPrivate();
} catch (OopError $error) {
    echo $error->getMessage() . PHP_EOL;
}

// Global usage tests:

$sample = new AccessModifiersSample();

$sample->setPublicAccess('public:global');
echo $sample->getPublicAccess() . PHP_EOL;

try {
    $sample->setProtectedAccess('protected:global');
} catch (OopError $error) {
    echo $error->getMessage() . PHP_EOL;
}

try {
    $sample->setPrivateAccess('private:global');
} catch (OopError $error) {
    echo $error->getMessage() . PHP_EOL;
}
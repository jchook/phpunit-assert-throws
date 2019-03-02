<?php declare(strict_types = 1);

use Jchook\AssertThrows\AssertThrows;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\TestCase;

final class DummyAssert extends Assert
{
    use AssertThrows;
}

function throwError()
{
    throw new Error();
}

function throwException()
{
    throw new Exception();
}

function dontThrow()
{
}

function inspectException(Exception $throwable)
{
    AbstractComparableTest::$inspected++;
}

function inspectError(Error $throwable)
{
    AbstractComparableTest::$inspected++;
}

final class AbstractComparableTest extends TestCase
{
    public static $inspected = 0;

    protected function assertAssertionFails(callable $assertion, string $class, callable $execute)
    {
        try {
            $assertion($class, $execute);
        } catch (AssertionFailedError $e) {
            return;
        }
        $this->fail("Assertion shouldn't have succeeded.");
    }

    protected function assertAssertionSucceeds(callable $assertion, string $class, callable $execute)
    {
        $assertion($class, $execute);
    }

    public final function testAssertNotThrows()
    {
        $this->assertAssertionFails([new DummyAssert(), "assertNotThrows"], Exception::class, "throwException");
        $this->assertAssertionFails([new DummyAssert(), "assertNotThrows"], Error::class, "throwError");

        $this->assertAssertionSucceeds([new DummyAssert(), "assertNotThrows"], Error::class, "throwException");
        $this->assertAssertionSucceeds([new DummyAssert(), "assertNotThrows"], Exception::class, "throwError");

        $this->assertAssertionSucceeds([new DummyAssert(), "assertNotThrows"], Exception::class, "dontThrow");
        $this->assertAssertionSucceeds([new DummyAssert(), "assertNotThrows"], Error::class, "dontThrow");
    }

    public final function testAssertThrows()
    {
        $this->assertAssertionFails([new DummyAssert(), "assertThrows"], Error::class, "throwException");
        $this->assertAssertionFails([new DummyAssert(), "assertThrows"], Exception::class, "throwError");

        $this->assertAssertionFails([new DummyAssert(), "assertThrows"], Exception::class, "dontThrow");
        $this->assertAssertionFails([new DummyAssert(), "assertThrows"], Error::class, "dontThrow");

        $this->assertAssertionSucceeds([new DummyAssert(), "assertThrows"], Exception::class, "throwException");
        $this->assertAssertionSucceeds([new DummyAssert(), "assertThrows"], Error::class, "throwError");
    }

    public final function testAssertThrowsInspector()
    {
        (new DummyAssert())->assertThrows(Exception::class, "throwException", "inspectException");
        (new DummyAssert())->assertThrows(Exception::class, "throwException", "inspectException");
    }
}



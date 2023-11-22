# PHPUnit `assertThrows()`

Exception testing assertions for PHPUnit.

## Installation

You can install it with composer, if you use that.

```sh
composer require --dev jchook/phpunit-assert-throws
```

Alternatively, [download the one file](https://raw.githubusercontent.com/jchook/phpunit-assert-throws/master/src/AssertThrows.php) and require it.

## Rationale

PHPUnit's current [best practices](https://web.archive.org/web/20220524152124/https://thephp.cc/articles/questioning-phpunit-best-practices) (i.e. [expectException](http://phpunit.readthedocs.io/en/7.1/writing-tests-for-phpunit.html#writing-tests-for-phpunit-exceptions)) for exception handling do not meet [our expectations](https://github.com/sebastianbergmann/phpunit/issues/3071#issuecomment-379301478).

Instead, you can use this simple PHPUnit add-on to provide familiar and convenient exception testing.

- Supports multiple exceptions per test
- Supports assertions called after the exception is thrown
- Clear usage examples
- Standard `assert` syntax
- Supports assertions for more than just `message`, `code`, and `class`
- Supports inverse assertion, `assertNotThrows`

## Simple Example

Just to illustrate the spirit behind the syntax:

```php
// Within your test case...
$this->assertThrows(MyException::class, function() use ($obj) {
	$obj->doSomethingBad();
});
```

Pretty neat?

---

## Full Usage Example

The TestCase class below shows a more comprehensive usage example:

```php
<?php

declare(strict_types=1);

// PHPUnit
use PHPUnit\Framework\TestCase;

// This library
use Jchook\AssertThrows\AssertThrows;

// Your classes
use MyNamespace\MyException;
use MyNamespace\MyObject;

final class MyTest extends TestCase
{
	use AssertThrows; // <--- adds the assertThrows method

	public function testMyObject()
	{
		$obj = new MyObject();

		// Ensure that a function throws a specific exception
		$this->assertThrows(MyException::class, function() use ($obj) {
			$obj->doSomethingBad();
		});

		// Test custom aspects of a custom extension class
		$this->assertThrows(MyException::class,
			function() use ($obj) {
				$obj->doSomethingBad();
			},
			function($exception) {
				$this->assertEquals('Expected value', $exception->getCustomThing());
				$this->assertEquals(123, $exception->getCode());
			}
		);

		// Test that a specific method does *NOT* throw
		$this->assertNotThrows(MyException::class, function() use ($obj) {
			$obj->doSomethingGood();
		});
	}
}

?>
```

## Notes

You may think that `assertNotThrows()` feels grammatically… odd. However, it conforms with the PHPUnit naming conventions, such as [`assertNotContains()`](https://phpunit.de/manual/current/en/appendixes.assertions.html#appendixes.assertions.assertContains). Additionally, the PHPUnit team believes that [we don't need this inverse assertion](https://github.com/sebastianbergmann/phpunit-documentation/issues/171).

## License

MIT

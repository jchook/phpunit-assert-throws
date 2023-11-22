PHPUnit `assertThrows()`
========================

Advanced exception testing assertions for PHPUnit.


Installation
------------

You can install it with composer, if you use that.

```sh
composer require --dev jchook/phpunit-assert-throws
```

Alternatively, [download the one file](https://raw.githubusercontent.com/jchook/phpunit-assert-throws/master/src/AssertThrows.php) and require it.


Rationale
---------

Use this simple PHPUnit add-on for advanced exception testing with [PHPUnit](https://docs.phpunit.de/en/10.4/writing-tests-for-phpunit.html#expecting-exceptions).

- Throw multiple errors per test
- Examine and test errors after they are caught
- Copy-paste usage examples
- Use standard `assert*` syntax
- Test more than just `message`, `code`, and `class`
- Write simple happy-path tests with `assertNotThrows`


Example
-------

Just to illustrate the spirit behind the syntax:

```php
<?php

// Within your test case...
$x = new MyTestedObject();
$this->assertThrows(
  MyException::class,
  fn() => $x->doSomethingBad()
);
```

---


Advanced Example
----------------

The class below demonstrates more advanced features.

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

Yes, `assertNotThrows()` feels grammatically… odd. However, it conforms with the PHPUnit naming conventions, such as [`assertNotContains()`](https://phpunit.de/manual/current/en/appendixes.assertions.html#appendixes.assertions.assertContains). Additionally, the PHPUnit team believes that [we don't need this inverse assertion](https://github.com/sebastianbergmann/phpunit-documentation/issues/171).


## License

MIT

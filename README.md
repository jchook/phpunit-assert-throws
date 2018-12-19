# PHPUnit `assertThrows()`

Exception testing assertions for PHPUnit.

## Installation

You can install with composer if you're into that. Just make sure that your `vendor/autoload.php` file is included in your PHPUnit bootstrap file.

```sh
composer require --dev jchook/phpunit-assert-throws
```

Alternatively, simply [download the one file](https://raw.githubusercontent.com/jchook/phpunit-assert-throws/master/src/AssertThrows.php) and include it in your project.


## Rationale

PHPUnit's current "[best practices](https://thephp.cc/news/2016/02/questioning-phpunit-best-practices)" for exception testing seem.. lackluster ([docs](http://phpunit.readthedocs.io/en/7.1/writing-tests-for-phpunit.html#writing-tests-for-phpunit-exceptions)).

Since I [strongly disagree](https://github.com/sebastianbergmann/phpunit/issues/3071#issuecomment-379301478) with the current `expectException` implementation, I made a trait to use on my test cases.

* Supports multiple exceptions per test
* Supports assertions called after the exception is thrown
* Clear usage examples
* Standard `assert` syntax
* Supports assertions for more than just `message`, `code`, and `class`
* Supports inverse assertion, `assertNotThrows`


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

Here is an actual TestCase class that shows a more comprehensive usage example:

```php
<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Jchook\AssertThrows\AssertThrows;

// These are just for illustration
use MyNamespace\MyException;
use MyNamespace\MyObject;

final class MyTest extends TestCase
{
	use AssertThrows; // <--- adds the assertThrows method

	public function testMyObject()
	{
		$obj = new MyObject();

		// Test a basic exception is thrown
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

		// Test that a specific exception is *NOT* thrown
		$this->assertNotThrows(MyException::class, function() use ($obj) {
			$obj->doSomethingGood();
		});
	}
}

?>
```

## Notes

I realize that `assertNotThrows()` is grammatically... odd, but it's in keeping with the PHPUnit naming conventions, such as [`assertNotContains()`](https://phpunit.de/manual/current/en/appendixes.assertions.html#appendixes.assertions.assertContains). Additionally, the PHPUnit team's philosophy is that [this inverse assertion is not even needed](https://github.com/sebastianbergmann/phpunit-documentation/issues/171).


## License

MIT

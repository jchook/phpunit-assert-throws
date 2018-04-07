# PHPUnit `assertThrows()`

Exception testing assertions for PHPUnit.

## Installation

You can install with composer if you're into that:

```sh
composer require --dev jchook/phpunit-assert-throws
```

Or simply [download the one file](https://raw.githubusercontent.com/jchook/phpunit-assert-throws/master/src/AssertThrows.php) and include it in your project.


## Rationale

PHPUnit's current "[best practices](https://thephp.cc/news/2016/02/questioning-phpunit-best-practices)" for exception testing seem lackluster.

* Doesn't support multiple exceptions per test, or assertions called after the exception is thrown
* Documentation lacks useful or clear examples
* Non-standard and potentially confusing syntax ("expect" vs "assert")
* Only supports expectations for message, code, and exception class
* No inverse, such as "expectNotException"

I opened a [Github issue](https://github.com/sebastianbergmann/phpunit/issues/3071#issuecomment-379301478) for PHPUnit and the maintainer immediately dismissed it.

Since I strongly disagree with the current [`expectException`](http://phpunit.readthedocs.io/en/7.1/writing-tests-for-phpunit.html#writing-tests-for-phpunit-exceptions) implementation, I made this trait, which I `use` on my test cases.


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
```



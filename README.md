# phpunit-assert-thrown

Better exception testing assertions for PHPUnit.


## Rationale

PHPUnit's current "[best practices](https://thephp.cc/news/2016/02/questioning-phpunit-best-practices)" for exception testing are.. lackluster.

* Doesn't support multiple exceptions per test, or assertions called after the exception is thrown
* Documentation lacks useful or clear examples
* Non-standard and potentially confusing syntax ("expect" vs "assert")
* Only supports assertions for message, code, and class
* No inverse, such as "expectNoException"

I opened a [Github issue](https://github.com/sebastianbergmann/phpunit/issues/3071#issuecomment-379301478) for PHPUnit and was immediately dismissed by the maintainer.

Since I strongly disagree with the current [`expectException`](http://phpunit.readthedocs.io/en/7.1/writing-tests-for-phpunit.html#writing-tests-for-phpunit-exceptions) implementation, I made this trait, which I `use` on my test cases. Here is an example to illustrate usage:


## Simple Example

Just to illustrate the spirit behind the syntax correction:

```php    
// Within your test case...
$this->assertThrown(MyException::class, function() use ($obj) {
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

// You can autoload if desired
require_once __DIR__ . '/AssertThrown.php';

use PHPUnit\Framework\TestCase;

// These are just for illustration
use MyNamespace\MyException;
use MyNamespace\MyObject;

final class MyTest extends TestCase
{
	use AssertThrown; // <--- adds the assertThrown method

	public function testMyObject()
	{
		$obj = new MyObject();
		
		// Test a basic exception is thrown
		$this->assertThrown(MyException::class, function() use ($obj) {
			$obj->doSomethingBad();
		});
		
		// Test custom aspects of a custom extension class
		$this->assertThrown(MyException::class, 
			function() use ($obj) {
				$obj->doSomethingBad();
			},
			function($exception) {
				$this->assertEquals('Expected value', $exception->getCustomThing());
				$this->assertEquals(123, $exception->getCode());
			}
		);
		
		// Test that a specific exception is NOT thrown
		$this->assertNotThrown(MyException::class, function() use ($obj) {
			$obj->doSomethingGood();
		});
	}
}

```


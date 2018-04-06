<?php

namespace Jchook\AssertThrown;

use PHPUnit\Framework\Constraint\Exception as ConstraintException;
use PHPUnit\Framework\Constraint\LogicalNot;
use PHPUnit\Framework\ExpectationFailedException;

trait AssertThrown
{
	/**
	 * @param string $class
	 * @param callable $execute
	 */
	protected function assertNotThrown($class, callable $execute)
	{
		$exception = null;
		try {
			call_user_func($execute);
		} catch (\Exception $e) {
			$exception = $e;
		}
		if ($exception instanceof ExpectationFailedException) {
			throw $exception;
		}
		self::assertThat($exception, new LogicalNot(new ConstraintException($class)));
	}

	/**
	 * @param string $class
	 * @param callable $execute
	 * @param callable|null $inspect optional
	 */
	protected function assertThrown($class, callable $execute, callable $inspect = null)
	{
		$exception = null;
		try {
			call_user_func($execute);
		} catch (\Exception $e) {
			$exception = $e;
		}
		if ($exception instanceof ExpectationFailedException) {
			throw $exception;
		}
		self::assertThat($exception, new ConstraintException($class));
		if ($inspect) {
			call_user_func($inspect, $exception);
		}
	}
}
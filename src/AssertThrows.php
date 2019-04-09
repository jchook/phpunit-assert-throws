<?php declare(strict_types = 1);

/**
 * MIT License
 *
 * Copyright (c) 2018-2019 Wes Roberts, Librarian
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace Jchook\AssertThrows;

use PHPUnit\Framework\Constraint\Exception as ConstraintException;
use PHPUnit\Framework\Constraint\LogicalNot;
use PHPUnit\Framework\ExpectationFailedException;
use Throwable;

/**
 * Allows for multiple assertions checking for throwable without using multiple methods.
 *
 * @package Jchook\AssertThrows
 * @author  Wes Roberts <u36g@a.zinc.email>
 * @author  Librarian <librarians.studios@gmail.com>
 * @since   1.0.0
 */
trait AssertThrows
{
    /**
     * Asserts that the callable doesn't throw a specified exception.
     *
     * @param string   $class The exception type expected not to be thrown.
     * @param callable $execute The callable.
     * @since   1.0.0
     */
    public function assertNotThrows(string $class, callable $execute) : void
    {
        try {
            $execute();
        } catch (ExpectationFailedException $e) {
            throw $e;
        } catch (Throwable $e) {
            static::assertThat($e, new LogicalNot(new ConstraintException($class)));

            return;
        }

        static::assertThat(null, new LogicalNot(new ConstraintException($class)));
    }

    /**
     * Asserts that the callable throws a specified throwable.
     * If successful and the inspection callable is not null
     * then it is called and the caught exception is passed as argument.
     *
     * @param string        $class The exception type expected to be thrown.
     * @param callable      $execute The callable.
     * @param callable|null $inspect [optional] The inspector.
     * @since   1.0.0
     */
    public function assertThrows(
        string $class,
        callable $execute,
        callable $inspect = null
    ) : void {
        try {
            $execute();
        } catch (ExpectationFailedException $e) {
            throw $e;
        } catch (Throwable $e) {
            static::assertThat($e, new ConstraintException($class));

            if ($inspect !== null) {
                $inspect($e);
            }

            return;
        }
		
        static::assertThat(null, new ConstraintException($class));
    }
}

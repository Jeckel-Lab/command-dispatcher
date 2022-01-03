<?php

/**
 * @author: Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at: 06/12/2021
 */

namespace Tests\JeckelLab\CommandDispatcher\Exception;

use JeckelLab\CommandDispatcher\Exception\InvalidHandlerProvidedException;
use PHPUnit\Framework\TestCase;

/**
 * Class InvalidHandlerProvidedExceptionTest
 * @package Tests\JeckelLab\CommandDispatcher\Exception
 * @psalm-suppress PropertyNotSetInConstructor
 */
class InvalidHandlerProvidedExceptionTest extends TestCase
{
    public function testConstructWithObjectShouldWorks(): void
    {
        $exception = new InvalidHandlerProvidedException(new \stdClass());

        // phpcs:disable
        $this->assertEquals(
            'Invalid command handler provided, stdClass needs to implements JeckelLab\Contract\Core\CommandDispatcher\CommandHandler\CommandHandler interface',
            $exception->getMessage()
        );
        // phpcs:enable
    }

    public function testConstructWithStringShouldWorks(): void
    {
        $exception = new InvalidHandlerProvidedException('FooBar');

        // phpcs:disable
        $this->assertEquals(
            'Invalid command handler provided, handler needs to be an implementation of JeckelLab\Contract\Core\CommandDispatcher\CommandHandler\CommandHandler interface',
            $exception->getMessage()
        );
        // phpcs:enable
    }
}

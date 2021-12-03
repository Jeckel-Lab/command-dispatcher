<?php

/**
 * @author: Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at: 26/11/2021
 */

namespace Tests\JeckelLab\CommandDispatcher\CommandBus\Decorator;

use JeckelLab\CommandDispatcher\CommandBus\Decorator\DecoratedCommandBusUndefinedException;
use JeckelLab\CommandDispatcher\CommandBus\Decorator\LoggerDecorator;
use JeckelLab\CommandDispatcher\CommandResponse\CommandResponseFailure;
use JeckelLab\CommandDispatcher\CommandResponse\CommandResponseSuccess;
use JeckelLab\Contract\Core\CommandDispatcher\CommandBus\CommandBus;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Tests\JeckelLab\CommandDispatcher\Fixtures\FixtureCommand;

/**
 * Class CommandBusLoggerDecoratorTest
 * @package Tests\JeckelLab\CommandDispatcher\CommandBus\Decorator
 *  @psalm-suppress PropertyNotSetInConstructor
 */
class CommandBusLoggerDecoratorTest extends TestCase
{
    public function testDispatchWithoutDefinedDecoratedCommandBusShouldFail(): void
    {
        $command = new FixtureCommand();

        $commandBusDecorated = new LoggerDecorator();

        try {
            $commandBusDecorated->dispatch($command);
        } catch (DecoratedCommandBusUndefinedException $e) {
            $this->assertEquals(
                "You should call 'decorate()' method before dispatching commands on the decorator",
                $e->getMessage()
            );
            return;
        }
        $this->fail('Exception not thrown');
    }

    public function testDecorateWithoutLoggerAndSuccessResponseShouldNotFail(): void
    {
        $command = new FixtureCommand();
        $response = new CommandResponseSuccess();
        $commandBus = $this->createMock(CommandBus::class);
        $commandBus->expects($this->once())
            ->method('dispatch')
            ->with($command)
            ->willReturn($response);

        $commandBusDecorated = new LoggerDecorator();
        $commandBusDecorated->decorate($commandBus);

        $this->assertSame($response, $commandBusDecorated->dispatch($command));
    }

    public function testDecorateWithoutLoggerAndErrorResponseShouldNotFail(): void
    {
        $command = new FixtureCommand();
        $response = new CommandResponseFailure([], 'Failure');
        $commandBus = $this->createMock(CommandBus::class);
        $commandBus->expects($this->once())
            ->method('dispatch')
            ->with($command)
            ->willReturn($response);

        $commandBusDecorated = new LoggerDecorator();
        $commandBusDecorated->decorate($commandBus);

        $this->assertSame($response, $commandBusDecorated->dispatch($command));
    }

    public function testDecorateWithLoggerAndSuccessResponseShouldNotFail(): void
    {
        $command = new FixtureCommand();
        $response = new CommandResponseSuccess();
        $commandBus = $this->createMock(CommandBus::class);
        $commandBus->expects($this->once())
            ->method('dispatch')
            ->with($command)
            ->willReturn($response);

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->exactly(2))
            ->method('debug')
            ->withConsecutive(
                ['Start dispatch command: Tests\JeckelLab\CommandDispatcher\Fixtures\FixtureCommand'],
                ['Dispatch command Tests\JeckelLab\CommandDispatcher\Fixtures\FixtureCommand success']
            );

        $commandBusDecorated = new LoggerDecorator();
        $commandBusDecorated->decorate($commandBus);
        $commandBusDecorated->setLogger($logger);

        $this->assertSame($response, $commandBusDecorated->dispatch($command));
    }

    public function testDecorateWithLoggerAndErrorResponseShouldNotFail(): void
    {
        $command = new FixtureCommand();
        $response = new CommandResponseFailure([], 'Failure');
        $commandBus = $this->createMock(CommandBus::class);
        $commandBus->expects($this->once())
            ->method('dispatch')
            ->with($command)
            ->willReturn($response);

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())
            ->method('debug')
            ->with('Start dispatch command: Tests\JeckelLab\CommandDispatcher\Fixtures\FixtureCommand');
        $logger->expects($this->once())
            ->method('warning')
            ->with(
                'Dispatch command Tests\JeckelLab\CommandDispatcher\Fixtures\FixtureCommand failed because: Failure'
            );

        $commandBusDecorated = new LoggerDecorator();
        $commandBusDecorated->decorate($commandBus);
        $commandBusDecorated->setLogger($logger);

        $this->assertSame($response, $commandBusDecorated->dispatch($command));
    }
}

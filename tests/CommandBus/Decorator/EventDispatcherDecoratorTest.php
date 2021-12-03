<?php

/**
 * @author: Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at: 03/12/2021
 */

declare(strict_types=1);

namespace Tests\JeckelLab\CommandDispatcher\CommandBus\Decorator;

use JeckelLab\CommandDispatcher\CommandBus\Decorator\DecoratedCommandBusUndefinedException;
use JeckelLab\CommandDispatcher\CommandBus\Decorator\EventDispatcherDecorator;
use JeckelLab\CommandDispatcher\CommandResponse\CommandResponseSuccess;
use JeckelLab\Contract\Core\CommandDispatcher\CommandBus\CommandBus;
use JeckelLab\Contract\Domain\Event\Event;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;
use Tests\JeckelLab\CommandDispatcher\Fixtures\FixtureCommand;

/**
 * Class EventDispatcherDecoratorTest
 * @package Tests\JeckelLab\CommandDispatcher\CommandBus\Decorator
 * @psalm-suppress PropertyNotSetInConstructor
 */
class EventDispatcherDecoratorTest extends TestCase
{
    public function testDispatchWithoutDefinedDecoratedCommandBusShouldFail(): void
    {
        $command = new FixtureCommand();

        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $eventDispatcher->expects($this->never())
            ->method('dispatch');

        $commandBusDecorated = new EventDispatcherDecorator($eventDispatcher);

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

    public function testResponseWithoutEventsShouldDispatchNothing(): void
    {
        $command = new FixtureCommand();
        $response = new CommandResponseSuccess();
        $commandBus = $this->createMock(CommandBus::class);
        $commandBus->expects($this->once())
            ->method('dispatch')
            ->with($command)
            ->willReturn($response);

        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $eventDispatcher->expects($this->never())
            ->method('dispatch');

        $commandBusDecorated = new EventDispatcherDecorator($eventDispatcher);
        $commandBusDecorated->decorate($commandBus);
        $this->assertSame($response, $commandBusDecorated->dispatch($command));
    }

    public function testResponseWithMultipleEventsShouldDispatchAllEvents(): void
    {
        $event1 = $this->createMock(Event::class);
        $event2 = $this->createMock(Event::class);

        $command = new FixtureCommand();
        $response = new CommandResponseSuccess([$event1, $event2]);
        $commandBus = $this->createMock(CommandBus::class);
        $commandBus->expects($this->once())
            ->method('dispatch')
            ->with($command)
            ->willReturn($response);

        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $eventDispatcher->expects($this->exactly(2))
            ->method('dispatch')
            ->withConsecutive([$event1], [$event2]);

        $commandBusDecorated = new EventDispatcherDecorator($eventDispatcher);
        $commandBusDecorated->decorate($commandBus);
        $this->assertSame($response, $commandBusDecorated->dispatch($command));
    }
}

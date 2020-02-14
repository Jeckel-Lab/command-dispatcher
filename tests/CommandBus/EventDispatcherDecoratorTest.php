<?php

namespace Tests\JeckelLab\CommandDispatcher\CommandBus;

use JeckelLab\CommandDispatcher\Command\CommandInterface;
use JeckelLab\CommandDispatcher\CommandBus\CommandBusInterface;
use JeckelLab\CommandDispatcher\CommandBus\EventDispatcherDecorator;
use JeckelLab\CommandDispatcher\CommandResponse\CommandResponseInterface;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;
use stdClass;

class EventDispatcherDecoratorTest extends TestCase
{
    /**
     * @var CommandBusInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $commandBus;
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|EventDispatcherInterface
     */
    private $eventDispatcher;
    /**
     * @var CommandInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $command;
    /**
     * @var CommandResponseInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $response;

    protected function setUp(): void
    {
        parent::setUp();

        $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->commandBus = $this->createMock(CommandBusInterface::class);
        $this->command = $this->createMock(CommandInterface::class);
        $this->response = $this->createMock(CommandResponseInterface::class);
    }

    public function testDispatchWithoutEvent()
    {
        $this->commandBus->expects($this->once())
            ->method('dispatch')
            ->with($this->command)
            ->willReturn($this->response);

        $commandBus = new EventDispatcherDecorator($this->eventDispatcher, $this->commandBus);
        $response = $commandBus->dispatch($this->command);

        $this->assertSame($response, $this->response);
    }

    public function testDispatchWithEvent()
    {
        $event = new stdClass();

        $this->commandBus->expects($this->once())
            ->method('dispatch')
            ->with($this->command)
            ->willReturn($this->response);

        $this->response->expects($this->once())
            ->method('getEvents')
            ->willReturn([$event]);

        $this->eventDispatcher->expects($this->once())
            ->method('dispatch')
            ->with($event);

        $commandBus = new EventDispatcherDecorator($this->eventDispatcher, $this->commandBus);
        $response = $commandBus->dispatch($this->command);

        $this->assertSame($response, $this->response);
    }
}

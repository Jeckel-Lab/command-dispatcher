<?php

namespace Tests\JeckelLab\CommandDispatcher\CommandBus;

use JeckelLab\CommandDispatcher\CommandBus\EventDispatcherDecorator;
use JeckelLab\Contract\Core\CommandDispatcher\Command\Command;
use JeckelLab\Contract\Core\CommandDispatcher\CommandBus\CommandBus;
use JeckelLab\Contract\Core\CommandDispatcher\CommandResponse\CommandResponse;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;
use stdClass;

/**
 * Class EventDispatcherDecoratorTest
 * @package Tests\JeckelLab\CommandDispatcher\CommandBus
 */
class EventDispatcherDecoratorTest extends TestCase
{
    /**
     * @var CommandBus|MockObject
     */
    private $commandBus;
    /**
     * @var MockObject|EventDispatcherInterface
     */
    private $eventDispatcher;
    /**
     * @var Command|MockObject
     */
    private $command;
    /**
     * @var CommandResponse|MockObject
     */
    private $response;

    protected function setUp(): void
    {
        parent::setUp();

        $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->commandBus = $this->createMock(CommandBus::class);
        $this->command = $this->createMock(Command::class);
        $this->response = $this->createMock(CommandResponse::class);
    }

    public function testDispatchWithoutEvent(): void
    {
        $this->commandBus->expects($this->once())
            ->method('dispatch')
            ->with($this->command)
            ->willReturn($this->response);

        $commandBus = new EventDispatcherDecorator($this->eventDispatcher, $this->commandBus);
        $response = $commandBus->dispatch($this->command);

        $this->assertSame($response, $this->response);
    }

    public function testDispatchWithEvent(): void
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

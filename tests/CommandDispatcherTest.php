<?php
declare(strict_types=1);

namespace Tests\JeckelLab\ContainerDispatcher;

use JeckelLab\ContainerDispatcher\CommandDispatcher;
use JeckelLab\ContainerDispatcher\CommandHandlerInterface;
use JeckelLab\ContainerDispatcher\CommandInterface;
use JeckelLab\ContainerDispatcher\CommandResponseInterface;
use JeckelLab\ContainerDispatcher\Resolver\CommandHandlerResolverInterface;
use JeckelLab\ContainerDispatcher\Resolver\HandlerNotFoundException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;
use stdClass;

/**
 * Class CommandDispatcherTest
 */
final class CommandDispatcherTest extends TestCase
{
    /**
     * @var CommandInterface|MockObject
     */
    protected $command;

    /**
     * @var CommandResponseInterface|MockObject
     */
    protected $response;

    /**
     * @var CommandHandlerInterface|MockObject
     */
    protected $handler;

    /**
     * @var CommandHandlerResolverInterface|MockObject
     */
    protected $resolver;

    /**
     * @var MockObject|EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * setUp
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->command = $this->createMock(CommandInterface::class);
        $this->response = $this->createMock(CommandResponseInterface::class);
        $this->handler = $this->createMock(CommandHandlerInterface::class);
        $this->resolver = $this->createMock(CommandHandlerResolverInterface::class);
        $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class);
    }

    /**
     * @test dispatch
     */
    public function testDispatchWoEventDispatcher()
    {
        $this->response->expects($this->never())
            ->method('getEvents');

        $this->handler->expects($this->once())
            ->method('__invoke')
            ->with($this->command)
            ->willReturn($this->response);

        $this->resolver->expects($this->once())
            ->method('resolve')
            ->with($this->command)
            ->willReturn($this->handler);

        $dispatcher = new CommandDispatcher($this->resolver);

        $this->assertSame($this->response, $dispatcher->dispatch($this->command));
    }

    public function testDispatchWEventDispatcherWOEvents()
    {
        $this->response->expects($this->once())
            ->method('getEvents')
            ->willReturn(null);
        $this->handler->expects($this->once())
            ->method('__invoke')
            ->with($this->command)
            ->willReturn($this->response);
        $this->resolver->expects($this->once())
            ->method('resolve')
            ->with($this->command)
            ->willReturn($this->handler);
        $this->eventDispatcher->expects($this->never())
            ->method('dispatch');

        $dispatcher = new CommandDispatcher($this->resolver, $this->eventDispatcher);
        $this->assertSame($this->response, $dispatcher->dispatch($this->command));
    }

    public function testDispatchWEventDispatcherWEvent()
    {
        $event = new stdClass();

        $this->response->expects($this->once())
            ->method('getEvents')
            ->willReturn([$event]);
        $this->handler->expects($this->once())
            ->method('__invoke')
            ->with($this->command)
            ->willReturn($this->response);
        $this->resolver->expects($this->once())
            ->method('resolve')
            ->with($this->command)
            ->willReturn($this->handler);
        $this->eventDispatcher->expects($this->once())
            ->method('dispatch')
            ->with($event);

        $dispatcher = new CommandDispatcher($this->resolver, $this->eventDispatcher);
        $this->assertSame($this->response, $dispatcher->dispatch($this->command));
    }

    public function testDispatchWEventDispatcherWMultipleEvents()
    {
        $event1 = new stdClass();
        $event2 = new stdClass();

        $this->response->expects($this->once())
            ->method('getEvents')
            ->willReturn([$event1, $event2]);
        $this->handler->expects($this->once())
            ->method('__invoke')
            ->with($this->command)
            ->willReturn($this->response);
        $this->resolver->expects($this->once())
            ->method('resolve')
            ->with($this->command)
            ->willReturn($this->handler);
        $this->eventDispatcher->expects($this->exactly(2))
            ->method('dispatch')
            ->withConsecutive([$event1], [$event2]);

        $dispatcher = new CommandDispatcher($this->resolver, $this->eventDispatcher);
        $this->assertSame($this->response, $dispatcher->dispatch($this->command));
    }

    public function testDispatchWithErrorResolver()
    {
        $exception = new HandlerNotFoundException('foo bar');

        $this->resolver->expects($this->once())
            ->method('resolve')
            ->with($this->command)
            ->willThrowException($exception);

        $this->expectException(HandlerNotFoundException::class);

        (new CommandDispatcher($this->resolver))->dispatch($this->command);
    }
}

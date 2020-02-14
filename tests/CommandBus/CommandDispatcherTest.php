<?php

namespace Tests\JeckelLab\CommandDispatcher\CommandBus;

use JeckelLab\CommandDispatcher\Command\CommandInterface;
use JeckelLab\CommandDispatcher\CommandBus\CommandDispatcher;
use JeckelLab\CommandDispatcher\CommandHandler\CommandHandlerInterface;
use JeckelLab\CommandDispatcher\CommandResponse\CommandResponseInterface;
use JeckelLab\CommandDispatcher\Resolver\CommandHandlerResolverInterface;
use JeckelLab\CommandDispatcher\Resolver\HandlerNotFoundException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CommandDispatcherTest extends TestCase
{
    /**
     * @var CommandInterface|MockObject
     */
    private $command;
    /**
     * @var CommandResponseInterface|MockObject
     */
    private $response;
    /**
     * @var CommandHandlerInterface|MockObject
     */
    private $handler;
    /**
     * @var CommandHandlerResolverInterface|MockObject
     */
    private $resolver;

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
    }

    public function testDispatch()
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

    /**
     * Test dispatch when resolver throw an Exception (no handler founds)
     */
    public function testDispatchWithErrorResolver(): void
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

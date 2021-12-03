<?php

namespace Tests\JeckelLab\CommandDispatcher\CommandBus;

use JeckelLab\CommandDispatcher\CommandBus\CommandDispatcher;
use JeckelLab\CommandDispatcher\Resolver\CommandHandlerResolverInterface;
use JeckelLab\CommandDispatcher\Resolver\HandlerNotFoundException;
use JeckelLab\Contract\Core\CommandDispatcher\Command\Command;
use JeckelLab\Contract\Core\CommandDispatcher\CommandHandler\CommandHandler;
use JeckelLab\Contract\Core\CommandDispatcher\CommandResponse\CommandResponse;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class CommandDispatcherTest
 * @package Tests\JeckelLab\CommandDispatcher\CommandBus
 */
class CommandDispatcherTest extends TestCase
{
    /**
     * @var Command|MockObject
     */
    private $command;
    /**
     * @var CommandResponse|MockObject
     */
    private $response;
    /**
     * @var CommandHandler|MockObject
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

        $this->command = $this->createMock(Command::class);
        $this->response = $this->createMock(CommandResponse::class);
        $this->handler = $this->createMock(CommandHandler::class);
        $this->resolver = $this->createMock(CommandHandlerResolverInterface::class);
    }

    public function testDispatch(): void
    {
        $this->response->expects($this->never())
            ->method('events');

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

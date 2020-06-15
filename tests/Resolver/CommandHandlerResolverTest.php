<?php
declare(strict_types=1);

namespace Tests\JeckelLab\CommandDispatcher\Resolver;

use JeckelLab\CommandDispatcher\Resolver\CommandHandlerResolver;
use JeckelLab\CommandDispatcher\Resolver\HandlerNotFoundException;
use JeckelLab\Contract\Core\CommandDispatcher\Command\Command;
use JeckelLab\Contract\Core\CommandDispatcher\CommandHandler\CommandHandler;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/**
 * Class CommandHandlerResolverTest
 */
final class CommandHandlerResolverTest extends TestCase
{
    /**
     * Test resolve without defined handlers
     */
    public function testResolveWithNoHandlers(): void
    {
        $this->expectException(HandlerNotFoundException::class);

        $resolver = new CommandHandlerResolver();
        $resolver->resolve($this->createMock(Command::class));
    }

    /**
     * Test resolve without container
     */
    public function testResolveWithoutContainer(): void
    {
        $command = $this->createMock(Command::class);
        $handler = $this->createMock(CommandHandler::class);

        $resolver = new CommandHandlerResolver([get_class($command) => $handler]);
        $this->assertSame($handler, $resolver->resolve($command));
    }

    public function testResolveWithContainer(): void
    {
        $handlerName = 'command.handler';

        $command = $this->createMock(Command::class);
        $handler = $this->createMock(CommandHandler::class);
        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->once())
            ->method('has')
            ->with($handlerName)
            ->willReturn(true);
        $container->expects($this->once())
            ->method('get')
            ->with($handlerName)
            ->willReturn($handler);

        $resolver = new CommandHandlerResolver([get_class($command) => $handlerName], $container);
        $this->assertSame($handler, $resolver->resolve($command));
    }
}

<?php
declare(strict_types=1);

namespace Tests\JeckelLab\CommandDispatcher\Resolver;

use JeckelLab\CommandDispatcher\CommandHandler\CommandHandlerInterface;
use JeckelLab\CommandDispatcher\Command\CommandInterface;
use JeckelLab\CommandDispatcher\Resolver\CommandHandlerResolver;
use JeckelLab\CommandDispatcher\Resolver\HandlerNotFoundException;
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
        $resolver->resolve($this->createMock(CommandInterface::class));
    }

    /**
     * Test resolve without container
     */
    public function testResolveWithoutContainer(): void
    {
        $command = $this->createMock(CommandInterface::class);
        $handler = $this->createMock(CommandHandlerInterface::class);

        $resolver = new CommandHandlerResolver([get_class($command) => $handler]);
        $this->assertSame($handler, $resolver->resolve($command));
    }

    public function testResolveWithContainer(): void
    {
        $handlerName = 'command.handler';

        $command = $this->createMock(CommandInterface::class);
        $handler = $this->createMock(CommandHandlerInterface::class);
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

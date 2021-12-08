<?php
declare(strict_types=1);

namespace Tests\JeckelLab\CommandDispatcher\Resolver;

use JeckelLab\CommandDispatcher\Exception\HandlerNotFoundException;
use JeckelLab\CommandDispatcher\Resolver\CommandHandlerResolver;
use JeckelLab\CommandDispatcher\Exception\InvalidHandlerProvidedException;
use JeckelLab\Contract\Core\CommandDispatcher\Command\Command;
use JeckelLab\Contract\Core\CommandDispatcher\CommandBus\Exception\NoHandlerDefinedForCommandException;
use JeckelLab\Contract\Core\CommandDispatcher\CommandHandler\CommandHandler;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

/**
 * Class CommandHandlerResolverTest
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class CommandHandlerResolverTest extends TestCase
{
    /**
     * Test resolve without defined handlers
     */
    public function testResolveWithNoHandlers(): void
    {
        $this->expectException(NoHandlerDefinedForCommandException::class);

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

    public function testResolveWithContainerButServiceIsNotACommandHandler(): void
    {
        $handlerName = 'command.handler';

        $command = $this->createMock(Command::class);
        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->once())
            ->method('has')
            ->with($handlerName)
            ->willReturn(true);
        $container->expects($this->once())
            ->method('get')
            ->with($handlerName)
            ->willReturn(new \stdClass());

        $resolver = new CommandHandlerResolver([get_class($command) => $handlerName], $container);
        try {
            $resolver->resolve($command);
        } catch (\Throwable $e) {
            $this->assertInstanceOf(InvalidHandlerProvidedException::class, $e);
            // phpcs:disable
            $this->assertSame(
                'Invalid command handler provided, stdClass needs to implements ' . CommandHandler::class . ' interface',
                $e->getMessage()
            );
            // phpcs:enable
            return;
        }
        $this->fail('Exception not thrown');
    }

    public function testResolveWithContainerAndOverriddenCommand(): void
    {
        $handlerName = 'command.handler';

        $command = new class implements Command {
        };
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

        $resolver = new CommandHandlerResolver([Command::class => $handlerName], $container);
        $this->assertSame($handler, $resolver->resolve($command));
    }

    public function testResolveWithContainerThrowsException(): void
    {
        $handlerName = 'command.handler';

        $command = $this->createMock(Command::class);
        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->once())
            ->method('has')
            ->with($handlerName)
            ->willReturn(true);
        $container->expects($this->once())
            ->method('get')
            ->with($handlerName)
            ->willThrowException(new class extends \RuntimeException implements ContainerExceptionInterface {
            });

        $resolver = new CommandHandlerResolver([get_class($command) => $handlerName], $container);
        try {
            $resolver->resolve($command);
        } catch (\Throwable $e) {
            $this->assertInstanceOf(HandlerNotFoundException::class, $e);
            $this->assertSame(
                'No command handler instance for command.handler found in container for ' . get_class($command),
                $e->getMessage()
            );
            return;
        }
        $this->fail('Exception not thrown');
    }

    public function testResolveWithHandlerNotFoundInContainer(): void
    {
        $handlerName = 'command.handler';
        $command = $this->createMock(Command::class);
        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->once())
            ->method('has')
            ->with($handlerName)
            ->willReturn(false);

        $resolver = new CommandHandlerResolver([get_class($command) => $handlerName], $container);
        try {
            $resolver->resolve($command);
        } catch (\Throwable $e) {
            $this->assertInstanceOf(HandlerNotFoundException::class, $e);
            $this->assertSame(
                'No command handler instance for command.handler found in container for ' . get_class($command),
                $e->getMessage()
            );
            return;
        }
        $this->fail('Exception not thrown');
    }
}

<?php

/**
 * @author: Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at: 06/12/2021
 */

namespace Tests\JeckelLab\CommandDispatcher;

use JeckelLab\CommandDispatcher\CommandBus\CommandDispatcher;
use JeckelLab\CommandDispatcher\CommandBusBuilder;
use JeckelLab\CommandDispatcher\CommandResponse\CommandResponseSuccess;
use JeckelLab\CommandDispatcher\Exception\HandlerForCommandAlreadyDefinedException;
use JeckelLab\Contract\Core\CommandDispatcher\CommandBus\CommandBus;
use JeckelLab\Contract\Core\CommandDispatcher\CommandHandler\CommandHandler;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Tests\JeckelLab\CommandDispatcher\Fixtures\FixtureCommandBusDecorator;
use Tests\JeckelLab\CommandDispatcher\Fixtures\FixtureCommand;
use Tests\JeckelLab\CommandDispatcher\Fixtures\FixtureFailureCommandHandler;
use Tests\JeckelLab\CommandDispatcher\Fixtures\FixtureSuccessCommandHandler;

/**
 * Class CommandBusBuilderTest
 * @package Tests\JeckelLab\CommandDispatcher
 * @psalm-suppress PropertyNotSetInConstructor
 */
class CommandBusBuilderTest extends TestCase
{
    public function testBuildEmptyCommandBus(): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $commandBus = (new CommandBusBuilder($container))
            ->build();

        $this->assertInstanceOf(CommandBus::class, $commandBus);
        $this->assertInstanceOf(CommandDispatcher::class, $commandBus);
    }

    public function testBuildWithValidCommandHandler(): void
    {
        $commandResponse = new CommandResponseSuccess();
        $command = new FixtureCommand();

        $handler = $this->createMock(CommandHandler::class);
        $handler->expects($this->once())
            ->method('__invoke')
            ->with($command)
            ->willReturn($commandResponse);

        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->once())
            ->method('has')
            ->with(FixtureSuccessCommandHandler::class)
            ->willReturn(true);
        $container->expects($this->once())
            ->method('get')
            ->with(FixtureSuccessCommandHandler::class)
            ->willReturn($handler);

        $commandBus = (new CommandBusBuilder($container))
            ->addCommandHandler(FixtureSuccessCommandHandler::class)
            ->build();

        $this->assertInstanceOf(CommandBus::class, $commandBus);
        $this->assertInstanceOf(CommandDispatcher::class, $commandBus);
        $this->assertSame($commandResponse, $commandBus->dispatch($command));
    }

    public function testBuildWithTwoDifferentHandlerForSameCommandShouldFail(): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $builder = new CommandBusBuilder($container);

        try {
            $builder->addCommandHandler(
                FixtureSuccessCommandHandler::class,
                FixtureFailureCommandHandler::class
            );
        } catch (HandlerForCommandAlreadyDefinedException $e) {
            // phpcs:disable
            $this->assertEquals(
                'Another handler is already defined for command Tests\JeckelLab\CommandDispatcher\Fixtures\FixtureCommand (Defined handler: Tests\JeckelLab\CommandDispatcher\Fixtures\FixtureSuccessCommandHandler)',
                $e->getMessage()
            );
            // phpcs:enable
            return;
        }
        $this->fail('Exception not thrown');
    }

    public function testBuildWithTwiceSameHandlerForSameCommand(): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $builder = new CommandBusBuilder($container);

        $builder->addCommandHandler(
            FixtureSuccessCommandHandler::class,
            FixtureSuccessCommandHandler::class
        );

        $commandBus = $builder->build();

        $this->assertInstanceOf(CommandBus::class, $commandBus);
        $this->assertInstanceOf(CommandDispatcher::class, $commandBus);
    }

    public function testBuildWithSingleDecorator(): void
    {
        $decorator = $this->createStub(FixtureCommandBusDecorator::class);
        $decorator->expects($this->once())
            ->method('decorate')
            ->with($this->isInstanceOf(CommandDispatcher::class))
            ->will($this->returnSelf());

        $container = $this->createMock(ContainerInterface::class);
        $builder = new CommandBusBuilder($container);
        $builder->addDecorator($decorator);

        $commandBus = $builder->build();
        $this->assertSame($decorator, $commandBus);
    }

    public function testBuildWithChainedDecorators(): void
    {
        $decoratorOne = $this->createStub(FixtureCommandBusDecorator::class);
        $decoratorOne->expects($this->once())
            ->method('decorate')
            ->with($this->isInstanceOf(CommandDispatcher::class))
            ->will($this->returnSelf());

        $decoratorTwo =  $this->createStub(FixtureCommandBusDecorator::class);
        $decoratorTwo->expects($this->once())
            ->method('decorate')
            ->with($decoratorOne)
            ->will($this->returnSelf());

        $container = $this->createMock(ContainerInterface::class);
        $builder = new CommandBusBuilder($container);
        $builder->addDecorator($decoratorOne, $decoratorTwo);

        $commandBus = $builder->build();
        $this->assertSame($decoratorTwo, $commandBus);
    }
}

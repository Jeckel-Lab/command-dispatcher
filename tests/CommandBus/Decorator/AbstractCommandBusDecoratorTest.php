<?php

/**
 * @author: Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at: 06/12/2021
 */

namespace Tests\JeckelLab\CommandDispatcher\CommandBus\Decorator;

use JeckelLab\CommandDispatcher\CommandBus\Decorator\AbstractCommandBusDecorator;
use JeckelLab\CommandDispatcher\CommandResponse\CommandResponseSuccess;
use JeckelLab\CommandDispatcher\Exception\DecoratedCommandBusUndefinedException;
use JeckelLab\Contract\Core\CommandDispatcher\CommandBus\CommandBus;
use PHPUnit\Framework\TestCase;
use Tests\JeckelLab\CommandDispatcher\Fixtures\FixtureCommand;
use Tests\JeckelLab\CommandDispatcher\Fixtures\FixtureCommandBusDecorator;

class AbstractCommandBusDecoratorTest extends TestCase
{
    public function testDispatchWithoutDefinedDecoratedCommandBusShouldFail(): void
    {
        $command = new FixtureCommand();

        $commandBusDecorated = new FixtureCommandBusDecorator();

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

    public function testDispatchWithEmptyDecorator(): void
    {
        $command = new FixtureCommand();
        $response = new CommandResponseSuccess();
        $commandBus = $this->createMock(CommandBus::class);
        $commandBus->expects($this->once())
            ->method('dispatch')
            ->with($command)
            ->willReturn($response);

        $commandBusDecorated = new FixtureCommandBusDecorator();
        $commandBusDecorated->decorate($commandBus);

        $this->assertSame($response, $commandBusDecorated->dispatch($command));
    }
}

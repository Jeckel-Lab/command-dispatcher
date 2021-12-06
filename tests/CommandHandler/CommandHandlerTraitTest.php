<?php

/**
 * @author: Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at: 06/12/2021
 */

declare(strict_types=1);

namespace Tests\JeckelLab\CommandDispatcher\CommandHandler;

use JeckelLab\CommandDispatcher\CommandResponse\CommandResponseSuccess;
use JeckelLab\CommandDispatcher\Exception\InvalidCommandProvidedException;
use JeckelLab\Contract\Core\CommandDispatcher\Command\Command;
use PHPUnit\Framework\TestCase;
use Tests\JeckelLab\CommandDispatcher\Fixtures\FixtureCommand;
use Tests\JeckelLab\CommandDispatcher\Fixtures\FixtureCommandHandler;

/**
 * Class CommandHandlerTraitTest
 * @package Tests\JeckelLab\CommandDispatcher\CommandHandler
 * @psalm-suppress PropertyNotSetInConstructor
 */
class CommandHandlerTraitTest extends TestCase
{
    public function testValidateWithAcceptedCommand(): void
    {
        $handler = new FixtureCommandHandler();
        $this->assertInstanceOf(CommandResponseSuccess::class, $handler(new FixtureCommand()));
    }

    public function testValidateWithInvalidCommandShouldThrowException(): void
    {
        $handler = new FixtureCommandHandler();
        $command = $this->createMock(Command::class);
        try {
            $handler($command);
        } catch (\Throwable $e) {
            $this->assertInstanceOf(InvalidCommandProvidedException::class, $e);
            // phpcs:disable
            $this->assertSame(
                'Invalid command provided, expected Tests\JeckelLab\CommandDispatcher\Fixtures\FixtureCommand, but got ' . get_class($command) . '.',
                $e->getMessage()
            );
            // phpcs:enable
            return;
        }
        $this->fail('Exception not thrown');
    }
}

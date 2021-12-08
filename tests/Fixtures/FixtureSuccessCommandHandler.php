<?php

/**
 * @author: Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at: 24/11/2021
 */

declare(strict_types=1);

namespace Tests\JeckelLab\CommandDispatcher\Fixtures;

use JeckelLab\CommandDispatcher\CommandResponse\CommandResponseSuccess;
use JeckelLab\Contract\Core\CommandDispatcher\Command\Command;
use JeckelLab\Contract\Core\CommandDispatcher\CommandHandler\CommandHandler;
use JeckelLab\Contract\Core\CommandDispatcher\CommandResponse\CommandResponse;
use JeckelLab\Contract\Core\CommandDispatcher\Exception\InvalidCommandException;

/**
 * Class FixtureSuccessCommandHandler
 * @package Tests\JeckelLab\CommandBus\Fixtures*
 * @implements CommandHandler<FixtureCommand>
 */
class FixtureSuccessCommandHandler implements CommandHandler
{
    /**
     * @return array<class-string<Command>>
     * @psalm-mutation-free
     */
    public static function getHandledCommands(): array
    {
        return [ FixtureCommand::class ];
    }

    /**
     * @param FixtureCommand $command
     * @return CommandResponse
     * @throws InvalidCommandException
     */
    public function __invoke(Command $command): CommandResponse
    {
        return new CommandResponseSuccess();
    }
}

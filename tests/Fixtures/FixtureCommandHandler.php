<?php

/**
 * @author: Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at: 06/12/2021
 */

declare(strict_types=1);

namespace Tests\JeckelLab\CommandDispatcher\Fixtures;

use JeckelLab\CommandDispatcher\CommandHandler\CommandHandlerTrait;
use JeckelLab\CommandDispatcher\CommandResponse\CommandResponseSuccess;
use JeckelLab\Contract\Core\CommandDispatcher\Command\Command;
use JeckelLab\Contract\Core\CommandDispatcher\CommandHandler\CommandHandler;
use JeckelLab\Contract\Core\CommandDispatcher\CommandResponse\CommandResponse;

/**
 * Class FixtureCommandHandler
 * @package Tests\JeckelLab\CommandDispatcher\Fixtures
 * @implements CommandHandler<FixtureCommand>
 */
class FixtureCommandHandler implements CommandHandler
{
    /**
     * @use CommandHandlerTrait<FixtureCommand>
     */
    use CommandHandlerTrait;

    public static function getHandledCommands(): array
    {
        return [ FixtureCommand::class ];
    }

    public function __invoke(Command $command): CommandResponse
    {
        $this->validateCommand($command, self::getHandledCommands());
        return new CommandResponseSuccess();
    }
}

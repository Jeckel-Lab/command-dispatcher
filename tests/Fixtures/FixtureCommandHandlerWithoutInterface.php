<?php

/**
 * @author: Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at: 24/11/2021
 */

declare(strict_types=1);

namespace Tests\JeckelLab\CommandDispatcher\Fixtures;

use JeckelLab\CommandDispatcher\CommandResponse\CommandResponseSuccess;
use JeckelLab\Contract\Core\CommandDispatcher\Command\Command;
use JeckelLab\Contract\Core\CommandDispatcher\CommandResponse\CommandResponse;

/**
 * Class FixtureCommandHandlerWithoutInterface
 * @package Tests\JeckelLab\CommandBus\Fixtures
 */
class FixtureCommandHandlerWithoutInterface
{
    /**
     * @param Command $command
     * @return bool
     */
    public static function accept(Command $command): bool
    {
        return $command instanceof FixtureCommand;
    }

    /**
     * @param FixtureCommand $command
     * @return CommandResponse
     */
    public function __invoke(FixtureCommand $command): CommandResponse
    {
        return new CommandResponseSuccess();
    }
}

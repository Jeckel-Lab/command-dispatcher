<?php

/**
 * @author: Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at: 06/12/2021
 */

declare(strict_types=1);

namespace JeckelLab\CommandDispatcher\CommandHandler;

use JeckelLab\CommandDispatcher\Exception\InvalidCommandProvidedException;
use JeckelLab\Contract\Core\CommandDispatcher\Command\Command;

/**
 * @template CommandType of Command
 */
trait CommandHandlerTrait
{
    /**
     * @param Command $command
     * @param array<class-string<Command>> $expectedCommands
     * @psalm-assert CommandType $command
     */
    private function validateCommand(Command $command, array $expectedCommands): void
    {
        foreach ($expectedCommands as $handledCommand) {
            if ($command instanceof $handledCommand) {
                return;
            }
        }
        throw new InvalidCommandProvidedException(
            implode(', ', $expectedCommands),
            get_class($command)
        );
    }
}

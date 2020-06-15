<?php

/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 14/11/2019
 */

declare(strict_types=1);

namespace JeckelLab\CommandDispatcher\CommandHandler;

use JeckelLab\Contract\Core\CommandDispatcher\Command\Command;
use JeckelLab\Contract\Core\CommandDispatcher\CommandHandler\CommandHandler;
use JeckelLab\Contract\Core\CommandDispatcher\CommandResponse\CommandResponse;
use JeckelLab\Contract\Core\CommandDispatcher\Exception\InvalidCommandException;

/**
 * Class CommandHandlerAbstract
 * @package JeckelLab\CommandDispatcher\CommandHandler
 */
abstract class CommandHandlerAbstract implements CommandHandler
{
    /**
     * @param Command $command
     * @throws InvalidCommandException
     */
    protected function validateCommand(Command $command): void
    {
        foreach (static::getHandledCommands() as $handledCommand) {
            if ($command instanceof $handledCommand) {
                return;
            }
        }
        throw new InvalidCommandException(sprintf(
            'Invalid command "%s" provided to handler.',
            get_class($command)
        ));
    }

    /**
     * @param Command $command
     * @return CommandResponse
     */
    public function __invoke(Command $command): CommandResponse
    {
        $this->validateCommand($command);

        return $this->process($command);
    }

    /**
     * @param Command $command
     * @return CommandResponse
     */
    abstract protected function process(Command $command): CommandResponse;
}

<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 14/11/2019
 */

namespace JeckelLab\CommandDispatcher\CommandHandler;

use JeckelLab\CommandDispatcher\Command\CommandInterface;
use JeckelLab\CommandDispatcher\CommandResponse\CommandResponse;
use JeckelLab\CommandDispatcher\CommandResponse\CommandResponseInterface;
use JeckelLab\CommandDispatcher\Exception\InvalidCommandException;

/**
 * Class CommandHandlerAbstract
 * @package JeckelLab\CommandDispatcher\CommandHandler
 */
abstract class CommandHandlerAbstract implements CommandHandlerInterface
{
    /**
     * @param CommandInterface $command
     * @throws InvalidCommandException
     */
    protected function validateCommand(CommandInterface $command): void
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
     * @param CommandInterface $command
     * @return CommandResponseInterface
     */
    public function __invoke(CommandInterface $command): CommandResponseInterface
    {
        $this->validateCommand($command);

        return $this->process($command, new CommandResponse());
    }

    /**
     * @param CommandInterface         $command
     * @param CommandResponseInterface $commandResponse
     * @return CommandResponseInterface
     */
    abstract protected function process(
        CommandInterface $command,
        CommandResponseInterface $commandResponse
    ): CommandResponseInterface;
}

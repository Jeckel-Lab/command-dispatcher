<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 14/11/2019
 */

namespace JeckelLab\CommandDispatcher\CommandHandler;

use JeckelLab\CommandDispatcher\Command\CommandInterface;
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
        foreach (self::getHandledCommands() as $handledCommand) {
            if ($command instanceof $handledCommand) {
                return;
            }
        }
        throw new InvalidCommandException(sprintf(
            'Invalid command "%s" provided to handler.',
            get_class($command)
        ));
    }
}

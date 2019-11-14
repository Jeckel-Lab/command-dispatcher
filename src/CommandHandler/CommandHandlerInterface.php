<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 27/10/2019
 */

namespace JeckelLab\CommandDispatcher\CommandHandler;

use JeckelLab\CommandDispatcher\Command\CommandInterface;
use JeckelLab\CommandDispatcher\CommandResponse\CommandResponseInterface;
use JeckelLab\CommandDispatcher\Exception\InvalidCommandException;

/**
 * Interface CommandHandlerInterface
 * @package JeckelLab\CommandDispatcher\CommandHandler
 */
interface CommandHandlerInterface
{
    /**
     * @return string[]
     */
    public static function getHandledCommands(): array;

    /**
     * @param CommandInterface $command
     * @return CommandResponseInterface
     * @throws InvalidCommandException
     */
    public function __invoke(CommandInterface $command): CommandResponseInterface;
}

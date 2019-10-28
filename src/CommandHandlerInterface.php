<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 27/10/2019
 */

namespace JeckelLab\ContainerDispatcher;
/**
 * Interface CommandHandlerInterface
 * @package JeckelLab\ContainerDispatcher
 */
interface CommandHandlerInterface
{
    /**
     * @return array
     */
    public static function getHandledCommands(): array;

    /**
     * @param CommandInterface $command
     * @return CommandResponseInterface
     */
    public function __invoke(CommandInterface $command): CommandResponseInterface;
}

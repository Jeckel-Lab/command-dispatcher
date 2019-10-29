<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 27/10/2019
 */

namespace JeckelLab\ContainerDispatcher;

use JeckelLab\ContainerDispatcher\Command\CommandInterface;
use JeckelLab\ContainerDispatcher\CommandResponse\CommandResponseInterface;

/**
 * Interface ContainerDispatcherInterface
 */
interface CommandDispatcherInterface
{
    /**
     * @param CommandInterface $command
     * @return CommandResponseInterface
     */
    public function dispatch(CommandInterface $command): CommandResponseInterface;
}

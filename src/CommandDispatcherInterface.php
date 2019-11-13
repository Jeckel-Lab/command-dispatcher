<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 27/10/2019
 */

namespace JeckelLab\CommandDispatcher;

use JeckelLab\CommandDispatcher\Command\CommandInterface;
use JeckelLab\CommandDispatcher\CommandResponse\CommandResponseInterface;

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

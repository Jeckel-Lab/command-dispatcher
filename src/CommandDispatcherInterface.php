<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 27/10/2019
 */

namespace JeckelLab\ContainerDispatcher;

use JeckelLab\ContainerDispatcher\Command\CommandInterface;

/**
 * Interface ContainerDispatcherInterface
 */
interface CommandDispatcherInterface
{
    public function dispatch(CommandInterface $command): CommandResponseInterface;
}

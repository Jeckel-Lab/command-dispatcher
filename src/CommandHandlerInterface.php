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
     * @param CommandInterface $command
     * @return CommandResponseInterface
     */
    public function handle(CommandInterface $command): CommandResponseInterface;
}

<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 27/10/2019
 */

namespace JeckelLab\ContainerDispatcher\Resolver;

use JeckelLab\ContainerDispatcher\CommandHandlerInterface;
use JeckelLab\ContainerDispatcher\CommandInterface;

/**
 * Interface CommandHandlerResolverInterface
 * @package JeckelLab\ContainerDispatcher
 */
interface CommandHandlerResolverInterface
{
    /**
     * @param CommandInterface $command
     * @return CommandHandlerInterface
     * @throws HandlerNotFoundException
     */
    public function resolve(CommandInterface $command): CommandHandlerInterface;
}

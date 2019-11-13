<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 27/10/2019
 */

namespace JeckelLab\CommandDispatcher\Resolver;

use JeckelLab\CommandDispatcher\CommandHandler\CommandHandlerInterface;
use JeckelLab\CommandDispatcher\Command\CommandInterface;

/**
 * Interface CommandHandlerResolverInterface
 * @package JeckelLab\CommandDispatcher\Resolver
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

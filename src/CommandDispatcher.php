<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 27/10/2019
 */

namespace JeckelLab\ContainerDispatcher;

use JeckelLab\ContainerDispatcher\Resolver\CommandHandlerResolverInterface;

/**
 * Class CommandDispatcher
 * @package JeckelLab\ContainerDispatcher
 */
class CommandDispatcher implements CommandDispatcherInterface
{
    /**
     * @var CommandHandlerResolverInterface
     */
    protected $commandHandlerResolver;

    public function dispatch(CommandInterface $command): CommandResponseInterface
    {
        $handler = $this->commandHandlerResolver->resolve($command);

        return $handler->handle($command);
    }
}

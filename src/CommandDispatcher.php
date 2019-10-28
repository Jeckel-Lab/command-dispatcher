<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 27/10/2019
 */

namespace JeckelLab\ContainerDispatcher;

use JeckelLab\ContainerDispatcher\Resolver\CommandHandlerResolverInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

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

    /**
     * @var EventDispatcherInterface|null
     */
    protected $eventDispatcher;

    /**
     * CommandDispatcher constructor.
     * @param CommandHandlerResolverInterface $commandHandlerResolver
     * @param EventDispatcherInterface|null   $eventDispatcher
     */
    public function __construct(CommandHandlerResolverInterface $commandHandlerResolver, ?EventDispatcherInterface $eventDispatcher)
    {
        $this->commandHandlerResolver = $commandHandlerResolver;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param CommandInterface $command
     * @return CommandResponseInterface
     */
    public function dispatch(CommandInterface $command): CommandResponseInterface
    {
        $handler = $this->commandHandlerResolver->resolve($command);

        /** @var CommandResponseInterface $response */
        $response = $handler($command);

        if (null !== $this->eventDispatcher && null !== $response->getEvents()) {
            foreach($response->getEvents() as $event) {
                $this->eventDispatcher->dispatch($event);
            }
        }

        return $response;
    }
}

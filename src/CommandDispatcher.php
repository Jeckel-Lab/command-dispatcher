<?php

/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 27/10/2019
 */

declare(strict_types=1);

namespace JeckelLab\CommandDispatcher;

use JeckelLab\CommandDispatcher\Resolver\CommandHandlerResolverInterface;
use JeckelLab\Contract\Core\CommandDispatcher\Command\Command;
use JeckelLab\Contract\Core\CommandDispatcher\CommandBus\CommandBus;
use JeckelLab\Contract\Core\CommandDispatcher\CommandResponse\CommandResponse;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * Class CommandDispatcher
 * @package JeckelLab\CommandDispatcher
 * @Deprecated see JeckelLab\CommandDispatcher\CommandBus\CommandDispatcher instead and decorators
 */
class CommandDispatcher implements CommandBus
{
    /**
     * @var CommandHandlerResolverInterface
     */
    protected $resolver;

    /**
     * @var EventDispatcherInterface|null
     */
    protected $eventDispatcher;

    /**
     * CommandDispatcher constructor.
     * @param CommandHandlerResolverInterface $resolver
     * @param EventDispatcherInterface|null   $eventDispatcher
     */
    public function __construct(
        CommandHandlerResolverInterface $resolver,
        ?EventDispatcherInterface $eventDispatcher = null
    ) {
        $this->resolver = $resolver;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param Command $command
     * @return CommandResponse
     * @SuppressWarnings(PHPMD.IfStatementAssignment)
     */
    public function dispatch(Command $command): CommandResponse
    {
        $handler = $this->resolver->resolve($command);
        $response = $handler($command);

        if (null !== $this->eventDispatcher && null !== ($events = $response->getEvents())) {
            foreach ($events as $event) {
                $this->eventDispatcher->dispatch($event);
            }
        }

        return $response;
    }
}

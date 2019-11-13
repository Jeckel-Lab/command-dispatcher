<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 27/10/2019
 */

namespace JeckelLab\CommandDispatcher;

use JeckelLab\CommandDispatcher\Command\CommandInterface;
use JeckelLab\CommandDispatcher\CommandHandler\CommandHandlerInterface;
use JeckelLab\CommandDispatcher\CommandResponse\CommandResponseInterface;
use JeckelLab\CommandDispatcher\Resolver\CommandHandlerResolverInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * Class CommandDispatcher
 * @package JeckelLab\CommandDispatcher
 */
class CommandDispatcher implements CommandDispatcherInterface
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
     * @param CommandInterface $command
     * @return CommandResponseInterface
     * @SuppressWarnings(PHPMD.IfStatementAssignment)
     */
    public function dispatch(CommandInterface $command): CommandResponseInterface
    {
        /** @var CommandHandlerInterface $handler */
        $handler = $this->resolver->resolve($command);

        /** @var CommandResponseInterface $response */
        $response = $handler($command);

        if (null !== $this->eventDispatcher && null !== ($events = $response->getEvents())) {
            foreach ($events as $event) {
                $this->eventDispatcher->dispatch($event);
            }
        }

        return $response;
    }
}

<?php

/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 03/02/2020
 */

declare(strict_types=1);

namespace JeckelLab\CommandDispatcher\CommandBus;

use JeckelLab\CommandDispatcher\Command\CommandInterface;
use JeckelLab\CommandDispatcher\CommandResponse\CommandResponseInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * Class EventDispatcherDecorator
 * @package JeckelLab\CommandDispatcher\CommandBus
 */
class EventDispatcherDecorator implements CommandBusInterface
{
    /** @var EventDispatcherInterface */
    protected $eventDispatcher;

    /** @var CommandBusInterface */
    protected $next;

    /**
     * EventDispatcherDecorator constructor.
     * @param EventDispatcherInterface     $eventDispatcher
     * @param CommandBusInterface $next
     */
    public function __construct(EventDispatcherInterface $eventDispatcher, CommandBusInterface $next)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->next = $next;
    }

    /**
     * @param CommandInterface $command
     * @return CommandResponseInterface
     */
    public function dispatch(CommandInterface $command): CommandResponseInterface
    {
        $response = $this->next->dispatch($command);

        if (null !== ($events = $response->getEvents())) {
            foreach ($events as $event) {
                $this->eventDispatcher->dispatch($event);
            }
        }

        return $response;
    }
}

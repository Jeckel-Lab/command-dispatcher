<?php

/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 03/02/2020
 */

declare(strict_types=1);

namespace JeckelLab\CommandDispatcher\CommandBus;

use JeckelLab\Contract\Core\CommandDispatcher\Command\Command;
use JeckelLab\Contract\Core\CommandDispatcher\CommandBus\CommandBus;
use JeckelLab\Contract\Core\CommandDispatcher\CommandResponse\CommandResponse;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * Class EventDispatcherDecorator
 * @package JeckelLab\CommandDispatcher\CommandBus
 */
class EventDispatcherDecorator implements CommandBus
{
    /** @var EventDispatcherInterface */
    protected $eventDispatcher;

    /** @var CommandBus */
    protected $next;

    /**
     * EventDispatcherDecorator constructor.
     * @param EventDispatcherInterface     $eventDispatcher
     * @param CommandBus $next
     */
    public function __construct(EventDispatcherInterface $eventDispatcher, CommandBus $next)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->next = $next;
    }

    /**
     * @param Command $command
     * @return CommandResponse
     */
    public function dispatch(Command $command): CommandResponse
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

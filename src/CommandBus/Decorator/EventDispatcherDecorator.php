<?php

/**
 * @author: Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at: 03/12/2021
 */

declare(strict_types=1);

namespace JeckelLab\CommandDispatcher\CommandBus\Decorator;

use JeckelLab\Contract\Core\CommandDispatcher\Command\Command;
use JeckelLab\Contract\Core\CommandDispatcher\CommandResponse\CommandResponse;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * Class EventDispatcherDecorator
 * @package JeckelLab\CommandDispatcher\CommandBus\Decorator
 */
class EventDispatcherDecorator extends AbstractCommandBusDecorator
{
    /**
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(private EventDispatcherInterface $eventDispatcher)
    {
    }

    /**
     * @param Command         $command
     * @param CommandResponse $response
     * @return CommandResponse
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function postDispatch(Command $command, CommandResponse $response): CommandResponse
    {
        $events = $response->events();
        if (null === $events) {
            return $response;
        }
        foreach ($events as $event) {
            $this->eventDispatcher->dispatch($event);
        }
        return $response;
    }
}

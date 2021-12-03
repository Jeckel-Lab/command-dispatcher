<?php

/**
 * @author: Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at: 03/12/2021
 */

declare(strict_types=1);

namespace JeckelLab\CommandDispatcher\CommandBus\Decorator;

use JeckelLab\Contract\Core\CommandDispatcher\Command\Command;
use JeckelLab\Contract\Core\CommandDispatcher\CommandBus\CommandBus;
use JeckelLab\Contract\Core\CommandDispatcher\CommandResponse\CommandResponse;

/**
 * Class AbstractCommandBusDecorator
 * @package JeckelLab\CommandDispatcher\CommandBus\Decorator
 */
class AbstractCommandBusDecorator implements CommandBusDecorator
{
    /** @var CommandBus|null */
    private ?CommandBus $commandBus = null;

    /**
     * @param CommandBus $commandBus
     * @return CommandBusDecorator
     */
    public function decorate(CommandBus $commandBus): CommandBusDecorator
    {
        $this->commandBus = $commandBus;
        return $this;
    }

    /**
     * @param Command $command
     * @return CommandResponse
     */
    final public function dispatch(Command $command): CommandResponse
    {
        $this->preDispatch($command);
        if (null === $this->commandBus) {
            throw new DecoratedCommandBusUndefinedException();
        }
        return $this->postDispatch(
            $command,
            $this->commandBus->dispatch($command)
        );
    }

    /**
     * @param Command $command
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function preDispatch(Command $command): void
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
        return $response;
    }
}

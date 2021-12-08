<?php

/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 03/02/2020
 */

declare(strict_types=1);

namespace JeckelLab\CommandDispatcher\CommandBus;

use JeckelLab\CommandDispatcher\Resolver\CommandHandlerResolverInterface;
use JeckelLab\Contract\Core\CommandDispatcher\Command\Command;
use JeckelLab\Contract\Core\CommandDispatcher\CommandBus\CommandBus;
use JeckelLab\Contract\Core\CommandDispatcher\CommandResponse\CommandResponse;

/**
 * Class CommandDispatcher
 * @package JeckelLab\CommandDispatcher\CommandBus
 */
class CommandDispatcher implements CommandBus
{
    /**
     * CommandDispatcher constructor.
     * @param CommandHandlerResolverInterface $resolver
     */
    public function __construct(
        protected CommandHandlerResolverInterface $resolver
    ) {
    }

    /**
     * @param Command $command
     * @return CommandResponse
     * @SuppressWarnings(PHPMD.IfStatementAssignment)
     */
    public function dispatch(Command $command): CommandResponse
    {
        $handler = $this->resolver->resolve($command);
        return $handler($command);
    }
}

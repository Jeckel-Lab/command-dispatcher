<?php

/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 03/02/2020
 */

declare(strict_types=1);

namespace JeckelLab\CommandDispatcher\CommandBus;

use JeckelLab\CommandDispatcher\Command\CommandInterface;
use JeckelLab\CommandDispatcher\CommandHandler\CommandHandlerInterface;
use JeckelLab\CommandDispatcher\CommandResponse\CommandResponseInterface;
use JeckelLab\CommandDispatcher\Resolver\CommandHandlerResolverInterface;

/**
 * Class CommandDispatcher
 * @package JeckelLab\CommandDispatcher\CommandBus
 */
class CommandDispatcher implements CommandBusInterface
{
    /**
     * @var CommandHandlerResolverInterface
     */
    protected $resolver;

    /**
     * CommandDispatcher constructor.
     * @param CommandHandlerResolverInterface $resolver
     */
    public function __construct(
        CommandHandlerResolverInterface $resolver
    ) {
        $this->resolver = $resolver;
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

        return $response;
    }
}

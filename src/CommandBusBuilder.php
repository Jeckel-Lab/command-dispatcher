<?php

/**
 * @author: Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at: 03/12/2021
 */

declare(strict_types=1);

namespace JeckelLab\CommandDispatcher;

use JeckelLab\CommandDispatcher\CommandBus\CommandDispatcher;
use JeckelLab\CommandDispatcher\CommandBus\Decorator\CommandBusDecorator;
use JeckelLab\CommandDispatcher\Exception\HandlerForCommandAlreadyDefinedException;
use JeckelLab\CommandDispatcher\Resolver\CommandHandlerResolver;
use JeckelLab\Contract\Core\CommandDispatcher\Command\Command;
use JeckelLab\Contract\Core\CommandDispatcher\CommandBus\CommandBus;
use JeckelLab\Contract\Core\CommandDispatcher\CommandHandler\CommandHandler;
use Psr\Container\ContainerInterface;

class CommandBusBuilder
{
    /** @var array<class-string<Command>, CommandHandler|class-string<CommandHandler>> */
    private array $handlers = [];

    /** @var list<class-string<CommandBusDecorator>|CommandBusDecorator> */
    private array $decorators = [];

    public function __construct(private ContainerInterface $container)
    {
    }

    /**
     * @param CommandHandler|class-string<CommandHandler> ...$handlers
     * @return $this
     */
    public function addCommandHandler(string|CommandHandler ...$handlers): self
    {
        foreach ($handlers as $handler) {
            foreach ($handler::getHandledCommands() as $commandName) {
                if (isset($this->handlers[$commandName]) && $this->handlers[$commandName] !== $handler) {
                    /** @psalm-suppress MixedArgumentTypeCoercion */
                    throw new HandlerForCommandAlreadyDefinedException($commandName, $this->handlers[$commandName]);
                }
                $this->handlers[$commandName] = $handler;
            }
        }
        return $this;
    }

    /**
     * @param class-string<CommandBusDecorator>|CommandBusDecorator ...$decorators
     * @return $this
     */
    public function addDecorator(string|CommandBusDecorator ...$decorators): self
    {
        foreach ($decorators as $decorator) {
            $this->decorators[] = $decorator;
        }
        return $this;
    }

    public function build(): CommandBus
    {
        /** @psalm-suppress MixedArgumentTypeCoercion */
        $resolver = new CommandHandlerResolver($this->handlers, $this->container);
        /** @var CommandBus $commandBus */
        $commandBus = new CommandDispatcher($resolver);

        foreach ($this->decorators as $decorator) {
            /** @var CommandBusDecorator $instance */
            $instance = is_string($decorator) ? $this->container->get($decorator) : $decorator;

            /** @var CommandBus $commandBus */
            $commandBus = $instance->decorate($commandBus);
        }

        return $commandBus;
    }
}

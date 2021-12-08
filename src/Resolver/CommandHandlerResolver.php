<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 27/10/2019
 */

namespace JeckelLab\CommandDispatcher\Resolver;

use JeckelLab\CommandDispatcher\Exception\HandlerNotFoundException;
use JeckelLab\CommandDispatcher\Exception\InvalidHandlerProvidedException;
use JeckelLab\Contract\Core\CommandDispatcher\Command\Command;
use JeckelLab\Contract\Core\CommandDispatcher\CommandBus\Exception\NoHandlerDefinedForCommandException;
use JeckelLab\Contract\Core\CommandDispatcher\CommandHandler\CommandHandler;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

/**
 * Class CommandHandlerResolver
 * @package JeckelLab\CommandDispatcher\Resolver
 */
class CommandHandlerResolver implements CommandHandlerResolverInterface
{
    /**
     * @var array<class-string<Command>, CommandHandler<Command>>
     */
    private array $handlersInstance = [];

    /**
     * CommandHandlerResolver constructor.
     * @param array<class-string<Command>, CommandHandler|class-string<CommandHandler>> $handlers
     * @param ContainerInterface|null $container
     */
    public function __construct(
        protected array $handlers = [],
        protected ?ContainerInterface $container = null
    ) {
    }

    /**
     * @param Command $command
     * @return CommandHandler
     */
    public function resolve(Command $command): CommandHandler
    {
        if (! isset($this->handlersInstance[get_class($command)])) {
            $this->handlersInstance[get_class($command)] = $this->findHandlerInstance($command);
        }

        return $this->handlersInstance[get_class($command)];
    }

    /**
     * @param Command $command
     * @return CommandHandler
     */
    private function findHandlerInstance(Command $command): CommandHandler
    {
        $handler = $this->findConfiguredHandler($command);
        // $handler is already an instance
        if ($handler instanceof CommandHandler) {
            return $handler;
        }

        // $handler is only the name of the class, we need the instance from container
        if ($this->container !== null && $this->container->has($handler)) {
            try {
                $instance = $this->container->get($handler);
            } catch (ContainerExceptionInterface $e) {
                throw new HandlerNotFoundException(
                    message:  sprintf(
                        'No command handler instance for %s found in container for %s',
                        $handler,
                        get_class($command)
                    ),
                    previous: $e
                );
            }

            if (!$instance instanceof CommandHandler) {
                throw new InvalidHandlerProvidedException($instance);
            }
            return $instance;
        }

        throw new HandlerNotFoundException(
            sprintf(
                'No command handler instance for %s found in container for %s',
                $handler,
                get_class($command)
            )
        );
    }

    /**
     * @param Command $command
     * @return CommandHandler|class-string<CommandHandler>
     */
    private function findConfiguredHandler(Command $command): CommandHandler|string
    {
        // Find direct command handler
        if (isset($this->handlers[get_class($command)])) {
            return $this->handlers[get_class($command)];
        }

        // Find a command handler for a parent class or interface
        foreach ($this->handlers as $commandName => $handler) {
            /** @infection-ignore-all */
            if ($command instanceof $commandName || in_array($commandName, class_implements($command) ?: [], true)) {
                return $handler;
            }
        }

        throw new NoHandlerDefinedForCommandException($command);
    }
}

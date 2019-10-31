<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 27/10/2019
 */

namespace JeckelLab\ContainerDispatcher\Resolver;

use JeckelLab\ContainerDispatcher\CommandHandler\CommandHandlerInterface;
use JeckelLab\ContainerDispatcher\Command\CommandInterface;
use Psr\Container\ContainerInterface;

/**
 * Class CommandHandlerResolver
 * @package JeckelLab\ContainerDispatcher\Resolver
 */
class CommandHandlerResolver implements CommandHandlerResolverInterface
{
    /**
     * @var array
     */
    protected $handlers = [];

    /**
     * @var ContainerInterface|null
     */
    protected $container;

    /**
     * CommandHandlerResolver constructor.
     * @param ContainerInterface|null $container
     */
    public function __construct(?ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param CommandHandlerInterface $handler
     * @param array|null              $commands
     * @return $this
     */
    public function registerHandler(CommandHandlerInterface $handler, ?array $commands = null): self
    {
        $commands = $commands?: $handler::getHandledCommands();
        foreach ($commands as $commandName) {
            $this->handlers[$commandName] = $handler;
        }
        return $this;
    }

    /**
     * @param string $handlerService
     * @param array  $commands
     * @return $this
     */
    public function registerHandlerService(string $handlerService, array $commands): self
    {
        foreach ($commands as $commandName) {
            $this->handlers[$commandName] = $handlerService;
        }
        return $this;
    }

    /**
     * @param CommandInterface $command
     * @return CommandHandlerInterface
     */
    public function resolve(CommandInterface $command): CommandHandlerInterface
    {
        $handler = $this->findHandler($command);
        if ($handler instanceof CommandHandlerInterface) {
            return $handler;
        }

        if ($this->container !== null && $this->container->has($handler)) {
            return $this->container->get($handler);
        }

        throw new HandlerNotFoundException(sprintf(
            'No command handler instance for %s found in container for %s',
            $handler,
            get_class($command)
        ));
    }

    /**
     * @param CommandInterface $command
     * @return CommandHandlerInterface|string
     */
    protected function findHandler(CommandInterface $command)
    {
        if (isset($this->handlers[get_class($command)])) {
            return $this->handlers[get_class($command)];
        }

        foreach ($this->handlers as $commandName => $handler) {
            if ($command instanceof $commandName) {
                return $handler;
            }
        }

        throw new HandlerNotFoundException(sprintf('No command handler found for %s', get_class($command)));
    }
}

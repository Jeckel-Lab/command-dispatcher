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
     * @param array              $handlers
     * @param ContainerInterface $container
     */
    public function __construct(array $handlers = null, ?ContainerInterface $container = null)
    {
        $this->handlers = $handlers;
        $this->container = $container;
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

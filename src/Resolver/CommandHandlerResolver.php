<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 27/10/2019
 */

namespace JeckelLab\CommandDispatcher\Resolver;

use JeckelLab\Contract\Core\CommandDispatcher\Command\Command;
use JeckelLab\Contract\Core\CommandDispatcher\CommandHandler\CommandHandler;
use Psr\Container\ContainerInterface;

/**
 * Class CommandHandlerResolver
 * @package JeckelLab\CommandDispatcher\Resolver
 */
class CommandHandlerResolver implements CommandHandlerResolverInterface
{
    /**
     * @var array<CommandHandler|string>
     */
    protected $handlers = [];

    /**
     * @var ContainerInterface|null
     */
    protected $container;

    /**
     * CommandHandlerResolver constructor.
     * @param array<CommandHandler|string> $handlers
     * @param ContainerInterface|null               $container
     */
    public function __construct(array $handlers = [], ?ContainerInterface $container = null)
    {
        $this->handlers = $handlers;
        $this->container = $container;
    }

    /**
     * @param Command $command
     * @return CommandHandler
     */
    public function resolve(Command $command): CommandHandler
    {
        $handler = $this->findHandler($command);
        if ($handler instanceof CommandHandler) {
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
     * @param Command $command
     * @return CommandHandler|string
     */
    protected function findHandler(Command $command)
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

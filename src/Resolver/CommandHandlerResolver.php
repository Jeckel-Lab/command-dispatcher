<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 27/10/2019
 */

namespace JeckelLab\ContainerDispatcher\Resolver;

use JeckelLab\ContainerDispatcher\CommandHandlerInterface;
use JeckelLab\ContainerDispatcher\CommandInterface;

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
     * @param CommandInterface $command
     * @return CommandHandlerInterface
     */
    public function resolve(CommandInterface $command): CommandHandlerInterface
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

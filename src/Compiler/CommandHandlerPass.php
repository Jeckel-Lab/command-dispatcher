<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 12/11/2019
 */

namespace JeckelLab\ContainerDispatcher\Compiler;

use JeckelLab\ContainerDispatcher\CommandHandler\CommandHandlerInterface;
use JeckelLab\ContainerDispatcher\Resolver\CommandHandlerResolverInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class CommandHandlerPass
 * @package JeckelLab\ContainerDispatcher\Compiler
 */
class CommandHandlerPass implements CompilerPassInterface
{
    /**
     * @var string
     */
    protected $handlersTag;

    /**
     * CommandHandlerPass constructor.
     * @param string $handlersTag
     */
    public function __construct(string $handlersTag)
    {
        $this->handlersTag = $handlersTag;
    }

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container): void
    {
        $handlerMaps = [];
        // Find command handlers
        foreach ($container->findTaggedServiceIds($this->handlersTag) as $serviceId => $tags) {

            $classname = $container->getDefinition($serviceId)->getClass();

            $aliasId = 'command_handler.handler.alias.'.$classname;
            $container->setAlias($aliasId, $serviceId)->setPublic(true);

            /** @var array $commands */
            $commands = call_user_func([$classname, 'getHandledCommands']);
            foreach($commands as $command) {
                $handlerMaps[$command] = $aliasId;
            }
        }

        $container->setParameter('command_handler.handlers.map', $handlerMaps);
    }
}

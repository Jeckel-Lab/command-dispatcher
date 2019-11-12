<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 12/11/2019
 */

namespace JeckelLab\ContainerDispatcher;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class CommandHandlerPass
 * @package JeckelLab\ContainerDispatcher
 */
class CommandHandlerPass implements CompilerPassInterface
{
    protected $handlersTag = 'command_dispatcher.handler';

    public function process(ContainerBuilder $container)
    {
        var_dump('start');
        if (!$container->hasDefinition($this->handlersTag)) {
            var_dump('nothing');
            return;
        }

        // create channels necessary for the handlers
        foreach ($container->findTaggedServiceIds($this->handlersTag) as $id => $tags) {
            var_dump($id);
            var_dump($tags);
        }
    }
}

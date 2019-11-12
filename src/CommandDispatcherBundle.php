<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 12/11/2019
 */

namespace JeckelLab\ContainerDispatcher;

use JeckelLab\ContainerDispatcher\Compiler\RegisterServicesPass;
use JeckelLab\ContainerDispatcher\Compiler\CommandHandlerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class CommandDispatcherBundle
 * @package JeckelLab\ContainerDispatcher
 */
class CommandDispatcherBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegisterServicesPass());
        $container->addCompilerPass(new CommandHandlerPass('command_dispatcher.handler'));
    }
}

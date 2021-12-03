<?php

/**
 * @author: Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at: 03/12/2021
 */

declare(strict_types=1);

namespace JeckelLab\CommandDispatcher\CommandBus\Decorator;

use JeckelLab\Contract\Core\CommandDispatcher\CommandBus\CommandBus;

interface CommandBusDecorator extends CommandBus
{
    public function decorate(CommandBus $commandBus): CommandBusDecorator;
}

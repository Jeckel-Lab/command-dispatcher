<?php

/**
 * @author: Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at: 03/12/2021
 */

declare(strict_types=1);

namespace JeckelLab\CommandDispatcher\Exception;

use JeckelLab\Contract\Core\CommandDispatcher\CommandBus\Exception\CommandBusException;
use JeckelLab\Contract\Core\Exception\LogicException;

/**
 * Class DecoratedCommandBusUndefinedException
 * @package JeckelLab\CommandDispatcher\Exception
 * @psalm-immutable
 * @psalm-suppress MutableDependency
 */
class DecoratedCommandBusUndefinedException extends LogicException implements CommandBusException
{
    public function __construct()
    {
        parent::__construct("You should call 'decorate()' method before dispatching commands on the decorator");
    }
}

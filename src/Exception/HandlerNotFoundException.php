<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 27/10/2019
 */

namespace JeckelLab\CommandDispatcher\Exception;

use JeckelLab\Contract\Core\CommandDispatcher\CommandBus\Exception\CommandBusException;
use JeckelLab\Contract\Core\Exception\RuntimeException;

/**
 * Class HandlerNotFoundException
 * @package JeckelLab\CommandDispatcher\Exception
 * @psalm-immutable
 * @psalm-suppress MutableDependency
 */
class HandlerNotFoundException extends RuntimeException implements CommandBusException
{

}

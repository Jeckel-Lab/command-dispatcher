<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 27/10/2019
 */

namespace JeckelLab\CommandDispatcher\Resolver;

use JeckelLab\Contract\Core\Exception\RuntimeException;

/**
 * Class HandlerNotFoundException
 * @package JeckelLab\CommandDispatcher\Resolver
 * @psalm-immutable
 */
class HandlerNotFoundException extends RuntimeException
{

}

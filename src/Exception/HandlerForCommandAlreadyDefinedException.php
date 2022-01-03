<?php

/**
 * @author: Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at: 03/12/2021
 */

declare(strict_types=1);

namespace JeckelLab\CommandDispatcher\Exception;

use JeckelLab\Contract\Core\CommandDispatcher\Command\Command;
use JeckelLab\Contract\Core\CommandDispatcher\CommandHandler\CommandHandler;
use JeckelLab\Contract\Core\CommandDispatcher\Exception\CommandDispatcherException;
use RuntimeException;

/**
 * Class HandlerForCommandAlreadyDefinedException
 * @package JeckelLab\CommandDispatcher\Exception
 * @psalm-immutable
 * @psalm-suppress MutableDependency
 */
class HandlerForCommandAlreadyDefinedException extends RuntimeException implements CommandDispatcherException
{
    /**
     * @param class-string<Command> $commandName
     * @param class-string<CommandHandler>|CommandHandler $definedHandlerName*
     */
    public function __construct(string $commandName, string|CommandHandler $definedHandlerName)
    {
        parent::__construct(
            sprintf(
                'Another handler is already defined for command %s (Defined handler: %s)',
                $commandName,
                is_object($definedHandlerName) ? get_class($definedHandlerName) : $definedHandlerName
            )
        );
    }
}

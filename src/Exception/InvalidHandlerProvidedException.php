<?php

/**
 * @author: Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at: 03/12/2021
 */

declare(strict_types=1);

namespace JeckelLab\CommandDispatcher\Exception;

use JeckelLab\Contract\Core\CommandDispatcher\CommandBus\Exception\CommandBusException;
use JeckelLab\Contract\Core\CommandDispatcher\CommandHandler\CommandHandler;

/**
 * Class InvalidHandlerProvidedException
 * @package JeckelLab\CommandDispatcher\Exception
 * @psalm-immutable
 * @psalm-suppress MutableDependency
 */
class InvalidHandlerProvidedException extends \InvalidArgumentException implements CommandBusException
{
    /**
     * @param mixed $handlerClassName
     * @psalm-suppress ImpureFunctionCall
     */
    public function __construct(mixed $handlerClassName)
    {
        $message = match (true) {
            is_object($handlerClassName) => sprintf(
                'Invalid command handler provided, %s needs to implements %s interface',
                get_class($handlerClassName),
                CommandHandler::class
            ),
            is_string($handlerClassName) && class_exists($handlerClassName) => sprintf(
                'Invalid command handler provided, %s needs to implements %s interface',
                $handlerClassName,
                CommandHandler::class
            ),
            default => sprintf(
                'Invalid command handler provided, handler needs to be an implementation of %s interface',
                CommandHandler::class
            )
        };

        parent::__construct($message);
    }
}

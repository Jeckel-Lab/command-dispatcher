<?php

/**
 * @author: Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at: 09/03/2021
 */
declare(strict_types=1);

namespace JeckelLab\CommandDispatcher\Exception;

use JeckelLab\Contract\Core\CommandDispatcher\Exception\InvalidCommandTypeException;

/**
 * Class InvalidCommandProvidedException
 * @package JeckelLab\CommandDispatcher\CommandHandler
 * @psalm-immutable
 */
class InvalidCommandProvidedException extends InvalidCommandTypeException
{
    public function __construct(string $expectedCommandFqcn, string $providedCommandFqcn)
    {
        parent::__construct(
            sprintf(
                'Invalid command provided, expected %s, but got %s.',
                $expectedCommandFqcn,
                $providedCommandFqcn
            )
        );
    }
}

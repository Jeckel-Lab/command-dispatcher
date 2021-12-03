<?php

/**
 * @author: Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at: 03/12/2021
 */

declare(strict_types=1);

namespace JeckelLab\CommandDispatcher\CommandBus\Decorator;

use JeckelLab\Contract\Core\CommandDispatcher\Command\Command;
use JeckelLab\Contract\Core\CommandDispatcher\CommandResponse\CommandResponse;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

/**
 * Class LoggerDecorator
 * @package JeckelLab\CommandDispatcher\CommandBus\Decorator
 */
class LoggerDecorator extends AbstractCommandBusDecorator implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @param Command $command
     */
    protected function preDispatch(Command $command): void
    {
        $this->logger?->debug(
            sprintf('Start dispatch command: %s', get_class($command))
        );
    }

    /**
     * @param Command         $command
     * @param CommandResponse $response
     * @return CommandResponse
     */
    protected function postDispatch(Command $command, CommandResponse $response): CommandResponse
    {
        if ($response->isSuccess()) {
            $this->logger?->debug(sprintf('Dispatch command %s success', get_class($command)));
            return $response;
        }
        $this->logger?->warning(
            sprintf(
                'Dispatch command %s failed because: %s',
                get_class($command),
                $response->failureReason() ?? 'undefined'
            )
        );
        return $response;
    }
}

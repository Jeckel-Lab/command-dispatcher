<?php

/**
 * @author: Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at: 09/03/2021
 */
declare(strict_types=1);

namespace JeckelLab\CommandDispatcher\CommandResponse;

use JeckelLab\Contract\Core\CommandDispatcher\CommandResponse\CommandResponse as CommandResponseInterface;
use JeckelLab\Contract\Domain\Event\Event;

/**
 * Class CommandResponseFailure
 * @package JeckelLab\CommandDispatcher\CommandResponse
 * @psalm-immutable
 */
class CommandResponseFailure implements CommandResponseInterface
{
    /**
     * CommandResponseAbstract constructor.
     * @param iterable<Event>|null $events
     */
    public function __construct(protected ?iterable $events = null, protected ?string $failureReason = null)
    {
    }

    public function isSuccess(): bool
    {
        return false;
    }

    public function isFailure(): bool
    {
        return true;
    }

    public function failureReason(): ?string
    {
        return $this->failureReason;
    }

    public function events(): ?iterable
    {
        return $this->events;
    }
}

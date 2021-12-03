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
 * Class CommandResponseSuccess
 * @package JeckelLab\CommandDispatcher\CommandResponse
 * @psalm-immutable
 */
class CommandResponseSuccess implements CommandResponseInterface
{
    /**
     * CommandResponseAbstract constructor.
     * @param iterable<Event>|null $events
     */
    public function __construct(protected ?iterable $events = null)
    {
    }

    public function isSuccess(): bool
    {
        return true;
    }

    public function isFailure(): bool
    {
        return false;
    }

    public function failureReason(): ?string
    {
        return null;
    }

    /**
     * @return iterable<Event>|null
     */
    public function events(): ?iterable
    {
        return $this->events; /** @phpstan-ignore-line */
    }
}

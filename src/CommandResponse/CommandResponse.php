<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 29/10/2019
 */

namespace JeckelLab\CommandDispatcher\CommandResponse;

use JeckelLab\Contract\Core\CommandDispatcher\CommandResponse\CommandResponse as CommandResponseInterface;
use JeckelLab\Contract\Domain\Event\Event;

/**
 * Class CommandResponse
 * @package JeckelLab\CommandDispatcher\CommandResponse
 * @psalm-immutable
 */
class CommandResponse implements CommandResponseInterface
{
    /**
     * CommandResponseAbstract constructor.
     * @param bool          $ack
     * @param iterable<Event>|null $events
     * @param string|null   $failureReason
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function __construct(
        protected bool $ack = true,
        protected ?iterable $events = null,
        protected ?string $failureReason = null
    ) {
    }

    public function isSuccess(): bool
    {
        return $this->ack;
    }

    public function isFailure(): bool
    {
        return ! $this->ack;
    }

    public function failureReason(): ?string
    {
        return $this->failureReason;
    }

    /**
     * @return iterable<Event>|null
     */
    public function events(): ?iterable
    {
        return $this->events; /** @phpstan-ignore-line */
    }
}

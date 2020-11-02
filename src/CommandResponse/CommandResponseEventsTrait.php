<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 29/10/2019
 */

namespace JeckelLab\CommandDispatcher\CommandResponse;

use JeckelLab\Contract\Domain\Event\Event;

/**
 * Trait CommandResponseEventsTrait
 * @package JeckelLab\CommandDispatcher\CommandResponse
 * @psalm-immutable
 */
trait CommandResponseEventsTrait
{
    /**
     * @var iterable<Event>|null
     */
    protected $events;

    /**
     * @return iterable<Event>|null
     */
    public function getEvents(): ?iterable
    {
        return $this->events;
    }
}

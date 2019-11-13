<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 29/10/2019
 */

namespace JeckelLab\CommandDispatcher\CommandResponse;

/**
 * Trait CommandResponseEventsTrait
 * @package JeckelLab\CommandDispatcher\CommandResponse
 */
trait CommandResponseEventsTrait
{
    /**
     * @var iterable|null
     */
    protected $events;

    /**
     * @param iterable $events
     * @return self
     */
    public function setEvents(iterable $events): self
    {
        $this->events = $events;

        return $this;
    }

    /**
     * @return iterable|null
     */
    public function getEvents(): ?iterable
    {
        return $this->events;
    }
}

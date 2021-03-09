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
    use CommandResponseAckTrait;
    use CommandResponseEventsTrait;

    /**
     * CommandResponseAbstract constructor.
     * @param iterable<Event>|null $events
     */
    public function __construct(?iterable $events = null)
    {
        $this->ack = true;
        $this->events = $events;
    }
}

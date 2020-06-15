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
    use CommandResponseAckTrait;
    use CommandResponseEventsTrait;

    /**
     * CommandResponseAbstract constructor.
     * @param bool                 $ack
     * @param iterable<Event>|null $events
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function __construct(bool $ack = true, ?iterable $events = null)
    {
        $this->ack = $ack;
        $this->events = $events;
    }
}

<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 29/10/2019
 */

namespace JeckelLab\ContainerDispatcher\CommandResponse;

/**
 * Class CommandResponseAbstract
 * @package DDDI\Command
 */
class CommandResponse implements CommandResponseInterface
{
    use CommandResponseAckTrait;
    use CommandResponseEventsTrait;

    /**
     * CommandResponseAbstract constructor.
     * @param bool          $ack
     * @param iterable|null $events
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function __construct(bool $ack = true, ?iterable $events = null)
    {
        $this->ack = $ack;
        $this->events = $events;
    }
}

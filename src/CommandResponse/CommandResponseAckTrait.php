<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 29/10/2019
 */

namespace JeckelLab\CommandDispatcher\CommandResponse;

/**
 * Trait CommandResponseAckTrait
 * @package JeckelLab\CommandDispatcher\CommandResponse
 * @psalm-immutable
 */
trait CommandResponseAckTrait
{
    /** @var bool */
    protected $ack = true;

    /**
     * @return bool
     */
    public function isAck(): bool
    {
        return $this->ack;
    }

    /**
     * @return bool
     */
    public function isNack(): bool
    {
        return ! $this->ack;
    }
}

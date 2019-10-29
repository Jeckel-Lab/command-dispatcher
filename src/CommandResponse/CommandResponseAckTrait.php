<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 29/10/2019
 */

namespace JeckelLab\ContainerDispatcher\CommandResponse;

/**
 * Trait AckTrait
 * @package DDDI\Command
 */
trait CommandResponseAckTrait
{
    /** @var bool */
    protected $ack = true;

    /**
     * @return self
     */
    public function ack(): self
    {
        $this->ack = true;
        return $this;
    }

    /**
     * @return self
     */
    public function nack(): self
    {
        $this->ack = false;
        return $this;
    }

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

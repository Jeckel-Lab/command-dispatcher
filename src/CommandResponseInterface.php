<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 27/10/2019
 */

namespace JeckelLab\ContainerDispatcher;

/**
 * Interface CommandResponseInterface
 * @package JeckelLab\ContainerDispatcher
 */
interface CommandResponseInterface
{
    /**
     * @return bool
     */
    public function isAck(): bool;

    /**
     * @return bool
     */
    public function isNack(): bool;

    /**
     * @return null|iterable
     */
    public function getEvents(): ?iterable;
}

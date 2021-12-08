<?php

/**
 * @author: Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at: 06/12/2021
 */

namespace Tests\JeckelLab\CommandDispatcher\CommandResponse;

use JeckelLab\CommandDispatcher\CommandResponse\CommandResponseSuccess;
use JeckelLab\Contract\Domain\Event\Event;
use PHPUnit\Framework\TestCase;

/**
 * Class CommandResponseSuccessTest
 * @package Tests\JeckelLab\CommandDispatcher\CommandResponse
 * @psalm-suppress PropertyNotSetInConstructor
 */
class CommandResponseSuccessTest extends TestCase
{
    public function testConstructWithoutEventsShouldWorks(): void
    {
        $response = new CommandResponseSuccess();
        $this->assertTrue($response->isSuccess());
        $this->assertFalse($response->isFailure());
        $this->assertNull($response->failureReason());
        $this->assertEmpty($response->events());
    }

    public function testConstructWithEventsShouldWorks(): void
    {
        $event1 = $this->createMock(Event::class);
        $event2 = $this->createMock(Event::class);
        $response = new CommandResponseSuccess([$event1, $event2]);
        $this->assertTrue($response->isSuccess());
        $this->assertFalse($response->isFailure());
        $this->assertNull($response->failureReason());
        $this->assertEquals([$event1, $event2], $response->events());
    }
}

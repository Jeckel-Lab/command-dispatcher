<?php

/**
 * @author: Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at: 06/12/2021
 */

namespace Tests\JeckelLab\CommandDispatcher\CommandResponse;

use JeckelLab\CommandDispatcher\CommandResponse\CommandResponseFailure;
use JeckelLab\Contract\Domain\Event\Event;
use PHPUnit\Framework\TestCase;

/**
 * Class CommandResponseFailureTest
 * @package Tests\JeckelLab\CommandDispatcher\CommandResponse
 * @psalm-suppress PropertyNotSetInConstructor
 */
class CommandResponseFailureTest extends TestCase
{
    public function testConstructWithoutEventsShouldWorks(): void
    {
        $response = new CommandResponseFailure([], 'This is a failure');
        $this->assertFalse($response->isSuccess());
        $this->assertTrue($response->isFailure());
        $this->assertEquals('This is a failure', $response->failureReason());
        $this->assertEmpty($response->events());
    }

    public function testConstructWithEventsShouldWorks(): void
    {
        $event1 = $this->createMock(Event::class);
        $event2 = $this->createMock(Event::class);
        $response = new CommandResponseFailure([$event1, $event2], 'This is a another failure');
        $this->assertFalse($response->isSuccess());
        $this->assertTrue($response->isFailure());
        $this->assertEquals('This is a another failure', $response->failureReason());
        $this->assertEquals([$event1, $event2], $response->events());
    }
}

<?php
declare(strict_types=1);

namespace Tests\JeckelLab\CommandDispatcher\CommandResponse;

use JeckelLab\CommandDispatcher\CommandResponse\CommandResponse;
use JeckelLab\Contract\Domain\Event\Event;
use PHPUnit\Framework\TestCase;

/**
 * Class CommandResponseTest
 * @package CommandResponse
 */
final class CommandResponseTest extends TestCase
{
    public function testAck(): void
    {
        $response = new CommandResponse();
        $this->assertTrue($response->isAck());
        $this->assertFalse($response->isNack());

        $response = new CommandResponse(false);
        $this->assertFalse($response->isAck());
        $this->assertTrue($response->isNack());

        $response = new CommandResponse(true);
        $this->assertTrue($response->isAck());
        $this->assertFalse($response->isNack());
    }

    public function testConstructWithEvents(): void
    {
        $events = [
            $this->createMock(Event::class),
            $this->createMock(Event::class)
        ];
        $response = new CommandResponse(true, $events);
        $this->assertSame($events, $response->getEvents());
    }
}

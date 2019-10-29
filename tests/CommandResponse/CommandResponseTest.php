<?php
declare(strict_types=1);

namespace Tests\JeckelLab\ContainerDispatcher\CommandResponse;

use JeckelLab\ContainerDispatcher\CommandResponse\CommandResponse;
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

        $this->assertSame($response, $response->nack());
        $this->assertFalse($response->isAck());
        $this->assertTrue($response->isNack());

        $this->assertSame($response, $response->ack());
        $this->assertTrue($response->isAck());
        $this->assertFalse($response->isNack());
    }

    public function testNack():void
    {
        $response = new CommandResponse(false);
        $this->assertFalse($response->isAck());
        $this->assertTrue($response->isNack());

        $this->assertSame($response, $response->ack());
        $this->assertTrue($response->isAck());
        $this->assertFalse($response->isNack());

        $this->assertSame($response, $response->nack());
        $this->assertFalse($response->isAck());
        $this->assertTrue($response->isNack());
    }

    public function testSetAndGetEvents(): void
    {
        $response = new CommandResponse();

        $this->assertNull($response->getEvents());

        $events = ['foo', new \stdClass()];
        $this->assertSame($response, $response->setEvents($events));
        $this->assertSame($events, $response->getEvents());
    }

    public function testConstructWithEvents(): void
    {
        $events = ['foo', new \stdClass()];
        $response = new CommandResponse(true, $events);
        $this->assertSame($events, $response->getEvents());
    }
}

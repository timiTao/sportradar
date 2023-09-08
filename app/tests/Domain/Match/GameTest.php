<?php

declare(strict_types = 1);

namespace Domain\Match;

use PHPUnit\Framework\TestCase;
use Sportradar\Domain\Match\Events\GameStarted;
use Sportradar\Domain\Match\Game;

class GameTest extends TestCase
{
    public function testWhenGameStartThenHaveIdAssigned(): void
    {
        $expectedId = '1';
        $game = new Game($expectedId, 'home', 'away');
        $this->assertEquals($expectedId, $game->getId());
    }

    public function testWhenGameCanBeCreatedThenItWillReturnStartEvent(): void
    {
        $match = new Game('id', 'home', 'away');
        $events = $match->getEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(GameStarted::class, $events[0]);
    }
}

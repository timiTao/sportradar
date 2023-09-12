<?php

declare(strict_types = 1);

namespace Domain\Match\Events;

use PHPUnit\Framework\TestCase;
use Sportradar\Domain\Match\Events\GameStarted;

class GameStartedTest extends TestCase
{
    public function testWhenCreatedThenHasAllStartingData(): void
    {
        $expectedId = '1';
        $homeTeam = 'home';
        $awayTeam = 'away';
        $defaultScoreHome = 0;
        $defaultScoreAway = 0;

        $event = new GameStarted($expectedId, $homeTeam, $awayTeam, $defaultScoreHome, $defaultScoreAway);
        $this->assertEquals($expectedId, $event->getAggregateId());
        $this->assertEquals($homeTeam, $event->getHomeTeam());
        $this->assertEquals($awayTeam, $event->getAwayTeam());
        $this->assertEquals($defaultScoreHome, $event->getHomeTeamScore());
        $this->assertEquals($defaultScoreAway, $event->getAwayTeamScore());
    }
}

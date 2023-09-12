<?php

declare(strict_types = 1);

namespace Domain\Match\Events;

use Sportradar\Domain\Match\Events\GameHomeScoreUpdated;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Sportradar\Domain\Match\Events\GameStarted;

class GameHomeScoreUpdatedTest extends TestCase
{

    public function testWhenCreatedThenHasAllStartingData(): void
    {
        $expectedId = '1';
        $homeScore = 0;

        $event = new GameHomeScoreUpdated($expectedId, $homeScore);
        $this->assertEquals($expectedId, $event->getAggregateId());
        $this->assertEquals($homeScore, $event->getScore());
    }
}

<?php

declare(strict_types = 1);

namespace Domain\Match\Events;

use PHPUnit\Framework\TestCase;
use Sportradar\Domain\Match\Events\GameAwayScoreUpdated;
use Sportradar\Domain\Match\Events\GameHomeScoreUpdated;

class GameAwayScoreUpdatedTest extends TestCase
{

    public function testWhenCreatedThenHasAllStartingData(): void
    {
        $expectedId = '1';
        $expectedScore = 0;

        $event = new GameAwayScoreUpdated($expectedId, $expectedScore);
        $this->assertEquals($expectedId, $event->getAggregateId());
        $this->assertEquals($expectedScore, $event->getScore());
    }
}
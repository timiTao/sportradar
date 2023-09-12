<?php

declare(strict_types = 1);

namespace Domain\Game\Events;

use Sportradar\Domain\Game\Events\GameHomeScoreUpdated;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Sportradar\Domain\Game\Events\GameStarted;

class GameHomeScoreUpdatedTest extends TestCase
{

    public function testWhenCreatedThenHasAllStartingData(): void
    {
        $expectedId = '1';

        $event = new GameHomeScoreUpdated($expectedId);
        $this->assertEquals($expectedId, $event->getAggregateId());
    }
}

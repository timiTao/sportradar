<?php

declare(strict_types = 1);

namespace Domain\Game\Events;

use PHPUnit\Framework\TestCase;
use Sportradar\Domain\Game\Events\GameAwayScoreUpdated;

class GameAwayScoreUpdatedTest extends TestCase
{

    public function testWhenCreatedThenHasAllStartingData(): void
    {
        $expectedId = '1';

        $event = new GameAwayScoreUpdated($expectedId);
        $this->assertEquals($expectedId, $event->getAggregateId());
    }
}

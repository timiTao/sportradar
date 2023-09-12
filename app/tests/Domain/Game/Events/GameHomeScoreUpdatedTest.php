<?php

declare(strict_types = 1);

namespace Domain\Game\Events;

use PHPUnit\Framework\TestCase;
use Sportradar\Domain\Game\Events\GameHomeScoreUpdated;

class GameHomeScoreUpdatedTest extends TestCase
{

    public function testWhenCreatedThenHasAllStartingData(): void
    {
        $expectedId = '1';

        $event = new GameHomeScoreUpdated($expectedId);
        $this->assertEquals($expectedId, $event->getAggregateId());
    }
}

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

        $event = new GameHomeScoreUpdated($expectedId);
        $this->assertEquals($expectedId, $event->getAggregateId());
    }
}

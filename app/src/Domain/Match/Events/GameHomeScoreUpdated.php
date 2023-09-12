<?php

declare(strict_types = 1);

namespace Sportradar\Domain\Match\Events;

readonly class GameHomeScoreUpdated implements GameEvent
{
    public function __construct(
        private string $aggregateId
    )
    {
    }

    public function getAggregateId(): string
    {
        return $this->aggregateId;
    }
}

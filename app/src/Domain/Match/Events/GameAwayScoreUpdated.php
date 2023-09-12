<?php

declare(strict_types = 1);

namespace Sportradar\Domain\Match\Events;

readonly class GameAwayScoreUpdated implements GameEvent
{
    public function __construct(
        private string $aggregateId,
        private int $score
    )
    {
    }

    public function getAggregateId(): string
    {
        return $this->aggregateId;
    }

    public function getScore(): int
    {
        return $this->score;
    }
}

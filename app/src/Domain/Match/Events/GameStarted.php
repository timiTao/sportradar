<?php

declare(strict_types = 1);

namespace Sportradar\Domain\Match\Events;

class GameStarted
{
    public function __construct(
        private readonly string $aggregateId,
        private readonly string $homeTeam,
        private readonly string $awayTeam
    )
    {
    }

    public function getAggregateId(): string
    {
        return $this->aggregateId;
    }

    public function getHomeTeam(): string
    {
        return $this->homeTeam;
    }

    public function getAwayTeam(): string
    {
        return $this->awayTeam;
    }
}

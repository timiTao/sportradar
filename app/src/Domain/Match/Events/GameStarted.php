<?php

declare(strict_types = 1);

namespace Sportradar\Domain\Match\Events;

readonly class GameStarted implements GameEvent
{
    public function __construct(
        private string $aggregateId,
        private string $homeTeam,
        private string $awayTeam,
        private int $homeTeamScore,
        private int $awayTeamScore
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

    public function getHomeTeamScore(): int
    {
        return $this->homeTeamScore;
    }

    public function getAwayTeamScore(): int
    {
        return $this->awayTeamScore;
    }
}

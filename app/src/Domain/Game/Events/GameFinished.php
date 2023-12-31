<?php

declare(strict_types = 1);

namespace Sportradar\Domain\Game\Events;

readonly class GameFinished implements GameEvent
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

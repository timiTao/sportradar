<?php

declare(strict_types = 1);

namespace Sportradar\Domain\Game\Events;

interface GameEvent
{
    public function getAggregateId(): string;
}
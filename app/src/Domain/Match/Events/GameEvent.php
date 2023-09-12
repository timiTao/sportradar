<?php

declare(strict_types = 1);

namespace Sportradar\Domain\Match\Events;

interface GameEvent
{
    public function getAggregateId(): string;
}
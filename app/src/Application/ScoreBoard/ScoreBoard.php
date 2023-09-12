<?php

declare(strict_types = 1);

namespace Sportradar\Application\ScoreBoard;

use Sportradar\Domain\Game\Events\GameEvent;
use Sportradar\Domain\Game\Events\GameStarted;
use Sportradar\Domain\Game\Exceptions\InvalidEventStream;

class ScoreBoard
{
    private array $matches = [];


    public function getMatches(): array
    {
        return array_values($this->matches);
    }


    public function apply(GameEvent ...$events): void
    {
        foreach ($events as $event) {
            switch (true) {
                case $event instanceof GameStarted:
                    $this->handleGameStarted($event);
                    break;
                default:
                    throw InvalidEventStream::notSupported($event::class);
            }
        }
    }
    private function handleGameStarted(GameStarted $event): void
    {
        $this->matches[$event->getAggregateId()] = MatchDTO::start(
            $event->getHomeTeam(),
            $event->getAwayTeam(),
            $event->getHomeTeamScore(),
            $event->getAwayTeamScore()
        );
    }
}

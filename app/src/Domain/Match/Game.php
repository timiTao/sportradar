<?php

declare(strict_types = 1);

namespace Sportradar\Domain\Match;

use Sportradar\Domain\Match\Events\GameAwayScoreUpdated;
use Sportradar\Domain\Match\Events\GameEvent;
use Sportradar\Domain\Match\Events\GameFinished;
use Sportradar\Domain\Match\Events\GameHomeScoreUpdated;
use Sportradar\Domain\Match\Events\GameStarted;
use Sportradar\Domain\Match\Exceptions\InvalidEvent;

class Game
{
    private array $events;

    private string $id;

    private int $homeTeamScore;
    private int $awayTeamScore;

    private function __construct(
        GameEvent ...$events
    )
    {
        $this->events = $events;
        foreach ($events as $event) {
            switch (true) {
                case $event instanceof GameStarted:
                    $this->id = $event->getAggregateId();
                    $this->homeTeamScore = $event->getHomeTeamScore();
                    $this->awayTeamScore = $event->getAwayTeamScore();
                    break;
                case $event instanceof GameHomeScoreUpdated:
                    $this->homeTeamScore = $event->getScore();
                    break;
                case $event instanceof GameAwayScoreUpdated:
                    $this->awayTeamScore = $event->getScore();
                    break;
                case $event instanceof GameFinished:
                    break;
                default:
                    throw InvalidEvent::notSupported($event::class);
            }
        }
    }

    public static function create(
        string $id,
        string $homeTeam,
        string $awayTeam
    ): Game
    {
        return new self(
            new GameStarted($id, $homeTeam, $awayTeam, 0, 0)
        );
    }

    public static function reconstruct(
        array $events
    ): Game
    {
        return new self(...$events);
    }

    public function getEvents(): array
    {
        return $this->events;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function scoreHomeTeam(): void
    {
        $this->homeTeamScore++;
        $this->events[] = new GameHomeScoreUpdated($this->id, $this->homeTeamScore);
    }

    public function scoreAwayTeam(): void
    {
        $this->awayTeamScore++;
        $this->events[] = new GameAwayScoreUpdated($this->id, $this->awayTeamScore);
    }

    public function finishGame(): void
    {
        $this->events[] = new GameFinished($this->id);
    }
}

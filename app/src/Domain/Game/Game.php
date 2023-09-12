<?php

declare(strict_types = 1);

namespace Sportradar\Domain\Game;

use Sportradar\Domain\Game\Events\GameAwayScoreUpdated;
use Sportradar\Domain\Game\Events\GameEvent;
use Sportradar\Domain\Game\Events\GameFinished;
use Sportradar\Domain\Game\Events\GameHomeScoreUpdated;
use Sportradar\Domain\Game\Events\GameStarted;
use Sportradar\Domain\Game\Exceptions\ForbiddenScoringInFinishedGame;
use Sportradar\Domain\Game\Exceptions\InvalidEventStream;

class Game
{
    private array $events;

    private string $id;

    private bool $isFinished = false;

    private function __construct(
        GameEvent ...$events
    )
    {
        if (count($events) == 0 || !($events[0] instanceof GameStarted)) {
            throw InvalidEventStream::missingGameStart();
        }

        foreach ($events as $event) {
            switch (true) {
                case $event instanceof GameStarted:
                    $this->handleGameStarted($event);
                    break;
                case $event instanceof GameAwayScoreUpdated:
                    $this->handleScoreAwayUpdated($event);
                    break;
                case $event instanceof GameHomeScoreUpdated:
                    $this->handleScoreHomeUpdated($event);
                    break;
                case $event instanceof GameFinished:
                    $this->handleGameFinished($event);
                    break;
                default:
                    throw InvalidEventStream::notSupported($event::class);
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
        $this->handleScoreHomeUpdated(new GameHomeScoreUpdated($this->id));
    }

    public function scoreAwayTeam(): void
    {
        $this->handleScoreAwayUpdated(new GameAwayScoreUpdated($this->id));
    }

    public function finishGame(): void
    {
        $this->handleGameFinished(new GameFinished($this->id));
    }

    private function handleGameStarted(GameStarted $event): void
    {
        $this->id = $event->getAggregateId();
        $this->events[] = $event;
    }

    private function handleScoreHomeUpdated(GameHomeScoreUpdated $event): void
    {
        if ($this->isFinished) {
            throw ForbiddenScoringInFinishedGame::homeTeam($this->id);
        }
        $this->events[] = $event;
    }

    private function handleScoreAwayUpdated(GameAwayScoreUpdated $event): void
    {
        if ($this->isFinished) {
            throw ForbiddenScoringInFinishedGame::awayTeam($this->id);
        }
        $this->events[] = $event;
    }

    private function handleGameFinished(GameFinished $event): void
    {
        $this->isFinished = true;
        $this->events[] = $event;
    }
}

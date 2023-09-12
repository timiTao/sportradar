<?php

declare(strict_types = 1);

namespace Sportradar\Domain\Game;

use Sportradar\Domain\Game\Events\GameAwayScoreUpdated;
use Sportradar\Domain\Game\Events\GameEvent;
use Sportradar\Domain\Game\Events\GameFinished;
use Sportradar\Domain\Game\Events\GameHomeScoreUpdated;
use Sportradar\Domain\Game\Events\GameStarted;
use Sportradar\Domain\Game\Exceptions\ForbiddenScoringInFinishedGame;
use Sportradar\Domain\Game\Exceptions\InvalidEvent;

class Game
{
    private array $events;

    private string $id;

    private int $homeTeamScore;
    private int $awayTeamScore;

    private bool $isFinished = false;

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
                case $event instanceof GameAwayScoreUpdated:
                case $event instanceof GameHomeScoreUpdated:
                    break;
                case $event instanceof GameFinished:
                    $this->isFinished = true;
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
        if ($this->isFinished) {
            throw ForbiddenScoringInFinishedGame::homeTeam($this->id);
        }
        $this->homeTeamScore++;
        $this->events[] = new GameHomeScoreUpdated($this->id, $this->homeTeamScore);
    }

    public function scoreAwayTeam(): void
    {
        if ($this->isFinished) {
            throw ForbiddenScoringInFinishedGame::awayTeam($this->id);
        }
        $this->awayTeamScore++;
        $this->events[] = new GameAwayScoreUpdated($this->id, $this->awayTeamScore);
    }

    public function finishGame(): void
    {
        $this->isFinished = true;
        $this->events[] = new GameFinished($this->id);
    }
}

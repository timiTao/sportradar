<?php

declare(strict_types = 1);

namespace Sportradar\Application\ScoreBoard;

use SplDoublyLinkedList;
use Sportradar\Application\ScoreBoard\Exceptions\InvalidEventStream;
use Sportradar\Domain\Game\Events\GameAwayScoreUpdated;
use Sportradar\Domain\Game\Events\GameEvent;
use Sportradar\Domain\Game\Events\GameFinished;
use Sportradar\Domain\Game\Events\GameHomeScoreUpdated;
use Sportradar\Domain\Game\Events\GameStarted;

class ScoreBoard
{
    /**
     * @var SplDoublyLinkedList|GameDTO[]
     */
    private SplDoublyLinkedList $outcome;


    public function __construct()
    {
        $this->outcome = new SplDoublyLinkedList();
    }


    public function getOutcome(): iterable
    {
        return $this->outcome;
    }


    public function apply(GameEvent ...$events): void
    {
        foreach ($events as $event) {
            $updatedMatch = match (true) {
                $event instanceof GameStarted => $this->handleGameStarted($event),
                $event instanceof GameHomeScoreUpdated => $this->handleGameHomeScoreUpdated($event),
                $event instanceof GameAwayScoreUpdated => $this->handleGameAwayScoreUpdated($event),
                $event instanceof GameFinished => $this->handleGameFinished($event),
                default => throw InvalidEventStream::notSupported($event::class),
            };

            $this->updateMatch($updatedMatch);
        }
    }

    private function getGameDTO(string $id): GameDTO
    {
        foreach ($this->outcome as $gameDTO) {
            if ($gameDTO->getId() === $id) {
                return $gameDTO;
            }
        }

        throw InvalidEventStream::missingGameStart($id);

    }

    private function handleGameStarted(GameStarted $event): GameDTO
    {
        return GameDTO::start(
            $event->getAggregateId(),
            $event->getHomeTeam(),
            $event->getAwayTeam(),
            $event->getHomeTeamScore(),
            $event->getAwayTeamScore()
        );
    }

    private function handleGameHomeScoreUpdated(GameHomeScoreUpdated $event): GameDTO
    {
        return $this->getGameDTO($event->getAggregateId())->increaseHomeScore();
    }

    private function handleGameAwayScoreUpdated(GameAwayScoreUpdated $event): GameDTO
    {
        return $this->getGameDTO($event->getAggregateId())->increaseAwayScore();
    }

    private function handleGameFinished(GameFinished $event): GameDTO
    {
        return $this->getGameDTO($event->getAggregateId())->finish();
    }

    private function updateMatch(GameDTO $updatedMatch): void
    {
        if (0 == $this->outcome->count()) {
            $this->outcome->push($updatedMatch);
            return;
        }

        $shouldUpdate = true;
        $newOutCome = new SplDoublyLinkedList();
        foreach ($this->outcome as $match) {
            if ($updatedMatch->equals($match)) {
                if ($shouldUpdate && $updatedMatch->isStillOpenGame()) {
                    $newOutCome->push($updatedMatch);
                    $shouldUpdate = false;
                }
                continue;
            }

            if ($shouldUpdate && $match->getTotalScore() <= $updatedMatch->getTotalScore()) {
                $newOutCome->push($updatedMatch);
                $shouldUpdate = false;
            }
            $newOutCome->push($match);
        }
        if ($shouldUpdate && $updatedMatch->isStillOpenGame()) {
            $newOutCome->push($updatedMatch);
        }

        $this->outcome = $newOutCome;
    }
}

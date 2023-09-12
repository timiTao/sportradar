<?php

declare(strict_types = 1);

namespace Sportradar\Application\ScoreBoard;
readonly class GameDTO
{
    private function __construct(
        private string $id,
        private string $homeTeam,
        private string $awayTeam,
        private int    $homeScore,
        private int    $awayScore,
        private bool   $openGame = true
    )
    {
    }

    public function getTotalScore(): int
    {
        return $this->homeScore + $this->awayScore;
    }

    public function isStillOpenGame(): bool
    {
        return $this->openGame;
    }

    public static function start(
        string $id,
        string $homeTeam,
        string $awayTeam,
        int    $homeScore,
        int    $awayScore
    ): self
    {
        return new self(
            $id,
            $homeTeam,
            $awayTeam,
            $homeScore,
            $awayScore
        );
    }

    public function getId(): string
    {
        return $this->id;
    }

    /**
     * For testing only final outcome
     */
    public function __toString(): string
    {
        return sprintf('%s %d - %s %d',
            $this->homeTeam,
            $this->homeScore,
            $this->awayTeam,
            $this->awayScore
        );
    }

    public function increaseHomeScore(): self
    {
        return new self(
            $this->id,
            $this->homeTeam,
            $this->awayTeam,
            $this->homeScore + 1,
            $this->awayScore
        );
    }

    public function increaseAwayScore(): self
    {
        return new self(
            $this->id,
            $this->homeTeam,
            $this->awayTeam,
            $this->homeScore,
            $this->awayScore + 1
        );
    }

    public function equals(GameDTO $game): bool
    {
        return $this->id == $game->id;
    }

    public function finish(): self
    {
        return new self(
            $this->id,
            $this->homeTeam,
            $this->awayTeam,
            $this->homeScore,
            $this->awayScore,
            false
        );
    }
}

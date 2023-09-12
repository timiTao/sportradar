<?php

declare(strict_types = 1);

namespace Sportradar\Application\ScoreBoard;
readonly class MatchDTO
{
    private function __construct(
        private string $homeTeam,
        private string $awayTeam,
        private int    $homeScore,
        private int    $awayScore,
        private int    $updated,
    )
    {
    }

    public static function start(
        string $homeTeam,
        string $awayTeam,
        int    $homeScore,
        int    $awayScore
    ): self
    {
        return new self(
            $homeTeam,
            $awayTeam,
            $homeScore,
            $awayScore,
            time()
        );
    }

    public function getUpdated(): int
    {
        return $this->updated;
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


}

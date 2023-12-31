<?php

declare(strict_types = 1);

namespace Sportradar\Domain\Game\Exceptions;

class ForbiddenScoringInFinishedGame extends GameException
{

    public static function homeTeam(string $id): self
    {
        return new ForbiddenScoringInFinishedGame(sprintf(
            "You can't score in finished game %s for home team",
            $id
        ));
    }

    public static function awayTeam(string $id): self
    {
        return new ForbiddenScoringInFinishedGame(sprintf(
            "You can't score in finished game %s for away team",
            $id
        ));
    }
}

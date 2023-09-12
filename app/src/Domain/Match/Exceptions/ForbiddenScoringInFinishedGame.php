<?php

declare(strict_types = 1);

namespace Sportradar\Domain\Match\Exceptions;

class ForbiddenScoringInFinishedGame extends GameException
{

    public static function homeTeam(string $id): self
    {
        return new ForbiddenScoringInFinishedGame(sprintf(
            "You can't score in finished game %s for home team",
            $id
        ));
    }
}

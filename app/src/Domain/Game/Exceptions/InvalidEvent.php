<?php

declare(strict_types = 1);

namespace Sportradar\Domain\Game\Exceptions;

use Sportradar\Domain\Game\Game;

class InvalidEvent extends GameException
{
    public static function notSupported(string $class): self
    {
        return new self(sprintf("Invalid '%s' for '%s' class", $class, Game::class));
    }
}

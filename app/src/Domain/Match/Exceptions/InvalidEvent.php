<?php

declare(strict_types = 1);

namespace Sportradar\Domain\Match\Exceptions;

use Sportradar\Domain\Match\Game;

class InvalidEvent extends GameException
{
    public static function notSupported(string $class): self
    {
        return new self(sprintf("Invalid '%s' for '%s' class", $class, Game::class));
    }
}

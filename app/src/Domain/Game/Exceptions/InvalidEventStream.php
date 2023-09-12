<?php

declare(strict_types = 1);

namespace Sportradar\Domain\Game\Exceptions;

use Sportradar\Domain\Game\Game;

class InvalidEventStream extends GameException
{
    public static function notSupported(string $class): self
    {
        return new self(sprintf("Invalid '%s' for '%s' class", $class, Game::class));
    }

    public static function missingGameStart(): self
    {
        return new self('Missing starting event in stream for reconstruction');
    }
}

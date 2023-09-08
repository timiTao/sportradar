<?php

declare(strict_types = 1);

namespace Sportradar\Domain\Match;

use Sportradar\Domain\Match\Events\GameStarted;

class Game
{
    private array $events = [];

    private string $id;


    public function __construct(
        string $id,
        string $homeTeam,
        string $awayTeam
    )
    {
        $this->id = $id;
        $this->events[] = new GameStarted($id, $homeTeam, $awayTeam);
    }

    public function getEvents(): array
    {
        return $this->events;
    }

    public function getId(): string
    {
        return $this->id;
    }
}

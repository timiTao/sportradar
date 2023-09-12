<?php

declare(strict_types = 1);

namespace Domain\Match;

use PHPUnit\Framework\TestCase;
use Sportradar\Domain\Match\Events\GameAwayScoreUpdated;
use Sportradar\Domain\Match\Events\GameEvent;
use Sportradar\Domain\Match\Events\GameFinished;
use Sportradar\Domain\Match\Events\GameHomeScoreUpdated;
use Sportradar\Domain\Match\Events\GameStarted;
use Sportradar\Domain\Match\Exceptions\InvalidEvent;
use Sportradar\Domain\Match\Game;

class GameTest extends TestCase
{
    public function testWhenGameStartThenHaveIdAssignedWithTeams(): void
    {
        $expectedId = '1';
        $game = Game::create($expectedId, 'home', 'away');
        $this->assertEquals($expectedId, $game->getId());
    }

    public function testWhenGameCanBeCreatedThenItWillReturnStartEvent(): void
    {
        $game = Game::create('id', 'home', 'away');
        $events = $game->getEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(GameStarted::class, $events[0], print_r($events, true));
    }

    public function testWhenReconstructNotSupportedEventThenThrowException(): void
    {
        $this->expectException(InvalidEvent::class);

        $event = new class() implements GameEvent {
            public function getAggregateId(): string
            {
                return '1';
            }
        };
        Game::reconstruct([$event]);
    }

    public function testWhenHomeTeamScoreThenScoreUpdatedEventPublish(): void
    {
        $game = Game::create('id', 'home', 'away');
        $game->scoreHomeTeam();

        $events = $game->getEvents();

        $this->assertCount(2, $events);
        $gameEvent = $events[1];
        $this->assertInstanceOf(GameHomeScoreUpdated::class, $gameEvent, print_r($events, true));
        $this->assertEquals(1, $gameEvent->getScore());
    }

    public function testWhenConsumeGameStartedThenSuccess(): void
    {
        $game = Game::reconstruct([
            new GameStarted('1', 'h', 'a', 0, 0)
        ]);
        $this->assertInstanceOf(Game::class, $game);
    }

    public function testWhenConsumeGameHomeScoreUpdatedThenSuccess(): void
    {
        $game = Game::reconstruct([
            new GameHomeScoreUpdated('1', 2)
        ]);
        $this->assertInstanceOf(Game::class, $game);
    }

    public function testWhenAwayTeamScoreThenScoreUpdatedEventPublish(): void
    {
        $game = Game::create('id', 'home', 'away');
        $game->scoreAwayTeam();

        $events = $game->getEvents();

        $this->assertCount(2, $events);
        $gameEvent = $events[1];
        $this->assertInstanceOf(GameAwayScoreUpdated::class, $gameEvent, print_r($events, true));
        $this->assertEquals(1, $gameEvent->getScore());
    }

    public function testWhenConsumeGameAwayScoreUpdatedThenSuccess(): void
    {
        $game = Game::reconstruct([
            new GameAwayScoreUpdated('1', 2)
        ]);
        $this->assertInstanceOf(Game::class, $game);
    }

    public function testWhenGameIsFinishThenGameFinishedEventPublished(): void
    {
        $game = Game::create('id', 'home', 'away');
        $game->finishGame();

        $events = $game->getEvents();

        $this->assertCount(2, $events);
        $this->assertInstanceOf(GameFinished::class, $events[1], print_r($events, true));
    }

    public function testWhenConsumeGameFinishedThenSuccess(): void
    {
        $game = Game::reconstruct([
            new GameFinished('1')
        ]);
        $this->assertInstanceOf(Game::class, $game);
    }
}

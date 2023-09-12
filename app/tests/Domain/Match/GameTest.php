<?php

declare(strict_types = 1);

namespace Domain\Match;

use PHPUnit\Framework\TestCase;
use Sportradar\Domain\Match\Events\GameAwayScoreUpdated;
use Sportradar\Domain\Match\Events\GameEvent;
use Sportradar\Domain\Match\Events\GameFinished;
use Sportradar\Domain\Match\Events\GameHomeScoreUpdated;
use Sportradar\Domain\Match\Events\GameStarted;
use Sportradar\Domain\Match\Exceptions\ForbiddenScoringInFinishedGame;
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

    public static function providerWhenBothTeamScoresThenCorrespondingNumberOverScoreUpdateEventIsPublished(): array
    {
        return [
            'double score home team' => [2, 0, 'h', 'a', ['h', 'h']],
            'double score away team' => [0, 2, 'h', 'a', ['a', 'a']],
            'mixed score both team' => [4, 3, 'h', 'a', ['h', 'a', 'h', 'a', 'h', 'a', 'h']],
        ];
    }

    /**
     * @dataProvider providerWhenBothTeamScoresThenCorrespondingNumberOverScoreUpdateEventIsPublished
     */
    public function testWhenBothTeamScoresThenCorrespondingNumberOverScoreUpdateEventIsPublished(
        int    $expectedHomeScore,
        int    $expectedAwayScore,
        string $homeTeam,
        string $awayTeam,
        array  $scenario
    ): void
    {
        $game = Game::create('id', $homeTeam, $awayTeam);

        foreach ($scenario as $scoreSide) {
            if ($scoreSide === $homeTeam) {
                $game->scoreHomeTeam();
            } else {
                $game->scoreAwayTeam();
            }
        }

        $gameHomeScoreEvents = array_filter($game->getEvents(), static function ($event) {
            return $event instanceof GameHomeScoreUpdated;
        });
        $this->assertCount($expectedHomeScore, $gameHomeScoreEvents);

        $gameAwayScoreEvents = array_filter($game->getEvents(), static function ($event) {
            return $event instanceof GameAwayScoreUpdated;
        });
        $this->assertCount($expectedAwayScore, $gameAwayScoreEvents);
    }

    public function testWhenGameIsFinishThenThrowException(): void
    {
        $game = Game::create('id', 'home', 'away');
        $game->finishGame();

        $this->expectException(ForbiddenScoringInFinishedGame::class);
        $game->scoreHomeTeam();
    }

}

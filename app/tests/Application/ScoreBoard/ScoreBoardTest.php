<?php

declare(strict_types = 1);

namespace Application\ScoreBoard;

use PHPUnit\Framework\TestCase;
use Sportradar\Application\ScoreBoard\Exceptions\InvalidEventStream;
use Sportradar\Application\ScoreBoard\ScoreBoard;
use Sportradar\Domain\Game\Events\GameAwayScoreUpdated;
use Sportradar\Domain\Game\Events\GameFinished;
use Sportradar\Domain\Game\Events\GameHomeScoreUpdated;
use Sportradar\Domain\Game\Events\GameStarted;

class ScoreBoardTest extends TestCase
{
    public function testWhenGameNotStartedThenPrintingScoreShouldBeEmpty(): void
    {
        $board = new ScoreBoard();
        $this->assertCount(0, $board->getOutcome());
    }

    public function testWhenGameStartAndOneScoreThenPrintItUpdated(): void
    {
        $board = new ScoreBoard();
        $board->apply(
            new GameStarted('1', 'H', 'A', 0, 0),
            new GameAwayScoreUpdated('1')
        );

        $this->assertCount(1, $board->getOutcome());

        $expectedMatches = [
            'H 0 - A 1',
        ];
        foreach ($board->getOutcome() as $i => $match) {
            $this->assertEquals($expectedMatches[$i], (string)$match, print_r($board->getOutcome(), true));
        }
    }

    public function testWhenGameStartAndOneScoreAndFinishThenPrintIEmpty(): void
    {
        $board = new ScoreBoard();
        $board->apply(
            new GameStarted('1', 'H', 'A', 0, 0),
            new GameAwayScoreUpdated('1'),
            new GameFinished('1')
        );

        $this->assertCount(0, $board->getOutcome());
    }

    public function testWhenBoardHasMoreGameThenPrintAllGames(): void
    {
        $board = new ScoreBoard();
        $board->apply(
            new GameStarted('1', 'H', 'A', 0, 0),
            new GameStarted('2', 'H2', 'A2', 0, 0),
            new GameStarted('3', 'H3', 'A3', 0, 0),
        );

        $expectedMatches = [
            'H3 0 - A3 0',
            'H2 0 - A2 0',
            'H 0 - A 0',
        ];
        $outcome = $board->getOutcome();
        $this->assertCount(3, $outcome);

        foreach ($outcome as $i => $match) {
            $this->assertEquals($expectedMatches[$i], (string)$match, print_r($outcome, true));
        }
    }

    public function testWhenBoardHasMoreGameThenPrintAllGamesFromHighestScore(): void
    {
        $board = new ScoreBoard();
        $board->apply(
            new GameStarted('1', 'H', 'A', 0, 0),
            new GameStarted('2', 'H2', 'A2', 0, 0),
            new GameStarted('3', 'H3', 'A3', 0, 0),
            new GameHomeScoreUpdated('2'),
            new GameHomeScoreUpdated('2'),
            new GameAwayScoreUpdated('2'),
            new GameAwayScoreUpdated('1'),
        );

        $expectedMatches = [
            'H2 2 - A2 1',
            'H 0 - A 1',
            'H3 0 - A3 0',
        ];
        $outcome = $board->getOutcome();
        $this->assertCount(3, $outcome);

        foreach ($outcome as $i => $match) {
            $this->assertEquals($expectedMatches[$i], (string)$match);
        }
    }

    public function testWhenMatchIsClosedAndTryScoreThenThrowException(): void
    {
        $this->expectException(InvalidEventStream::class);

        $board = new ScoreBoard();
        $board->apply(
            new GameStarted('1', 'H', 'A', 0, 0),
            new GameAwayScoreUpdated('1'),
            new GameFinished('1'),
            new GameAwayScoreUpdated('1')
        );
    }

    public function testWhenDeliverFinalTestThenIGotHired(): void
    {

        $board = new ScoreBoard();
        $board->apply(
            new GameStarted('1', 'Mexico', 'Canada', 0, 0),
            new GameAwayScoreUpdated('1'),
            new GameAwayScoreUpdated('1'),
            new GameAwayScoreUpdated('1'),
            new GameAwayScoreUpdated('1'),
            new GameAwayScoreUpdated('1')
        );
        $board->apply(
            new GameStarted('2', 'Spain', 'Brazil', 0, 0),
            new GameAwayScoreUpdated('2'),
            new GameAwayScoreUpdated('2'),
            new GameHomeScoreUpdated('2'),
            new GameHomeScoreUpdated('2'),
            new GameHomeScoreUpdated('2'),
            new GameHomeScoreUpdated('2'),
            new GameHomeScoreUpdated('2'),
            new GameHomeScoreUpdated('2'),
            new GameHomeScoreUpdated('2'),
            new GameHomeScoreUpdated('2'),
            new GameHomeScoreUpdated('2'),
            new GameHomeScoreUpdated('2')
        );

        $board->apply(
            new GameStarted('3', 'Germany', 'France', 0, 0),
            new GameAwayScoreUpdated('3'),
            new GameAwayScoreUpdated('3'),
            new GameHomeScoreUpdated('3'),
            new GameHomeScoreUpdated('3')
        );
        $board->apply(
            new GameStarted('4', 'Uruguay', 'Italy', 0, 0),
            new GameAwayScoreUpdated('4'),
            new GameAwayScoreUpdated('4'),
            new GameAwayScoreUpdated('4'),
            new GameAwayScoreUpdated('4'),
            new GameAwayScoreUpdated('4'),
            new GameAwayScoreUpdated('4'),
            new GameHomeScoreUpdated('4'),
            new GameHomeScoreUpdated('4'),
            new GameHomeScoreUpdated('4'),
            new GameHomeScoreUpdated('4'),
            new GameHomeScoreUpdated('4'),
            new GameHomeScoreUpdated('4')
        );

        $board->apply(
            new GameStarted('5', 'Argentina', 'Australia', 0, 0),
            new GameAwayScoreUpdated('5'),
            new GameHomeScoreUpdated('5'),
            new GameHomeScoreUpdated('5'),
            new GameHomeScoreUpdated('5')
        );

        $expectedGames = [
            'Uruguay 6 - Italy 6',
            'Spain 10 - Brazil 2',
            'Mexico 0 - Canada 5',
            'Argentina 3 - Australia 1',
            'Germany 2 - France 2',
        ];

        $outcome = $board->getOutcome();
        $this->assertCount(count($expectedGames), $outcome);

        foreach ($outcome as $i => $match) {
            $this->assertEquals($expectedGames[$i], (string)$match);
        }
    }
}

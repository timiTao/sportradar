<?php

declare(strict_types = 1);

namespace Application\ScoreBoard;

use PHPUnit\Framework\TestCase;
use Sportradar\Application\ScoreBoard\ScoreBoard;
use Sportradar\Domain\Game\Events\GameStarted;

class ScoreBoardTest extends TestCase
{
    public function testWhenGameNotStartedThenPrintingScoreShouldBeEmpty(): void
    {
        $board = new ScoreBoard();
        $this->assertCount(0, $board->getMatches());
    }

    public function testWhenGameHasMoreGameThenPrintAllGames(): void
    {
        $board = new ScoreBoard();
        $board->apply(
            new GameStarted('1', 'H', 'A', 0, 0),
            new GameStarted('2', 'H2', 'A2', 0, 0),
            new GameStarted('3', 'H3', 'A3', 0, 0),
        );

        $expectedMatches = [
            'H 0 - A 0',
            'H2 0 - A2 0',
            'H3 0 - A3 0',
        ];
        foreach ($board->getMatches() as $i => $match) {
            $this->assertEquals($expectedMatches[$i], (string)$match);
        }
    }
}

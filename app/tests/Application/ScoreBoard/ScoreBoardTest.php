<?php

declare(strict_types = 1);

namespace Application\ScoreBoard;

use Sportradar\Application\ScoreBoard\ScoreBoard;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class ScoreBoardTest extends TestCase
{
    public function testWhenGameStartedThenPrintingScoreShouldBeEmpty(): void
    {
        $board = new ScoreBoard();
        $this->assertEquals("", $board->print());
    }


}

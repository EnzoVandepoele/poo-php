<?php

declare(strict_types=1);

class Player {
    public int $level;

    public function __construct(int $level)
    {
        $this->level = $level;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function setLevel(int $level): void
    {
        $this->level = $level;
    }
}

class Encounter {
    private const RESULT_WINNER = 1;
    private const RESULT_LOSER = -1;
    private const RESULT_DRAW = 0;
    private const RESULT_POSSIBILITIES = [self::RESULT_WINNER, self::RESULT_LOSER, self::RESULT_DRAW];

    public static function probabilityAgainst(int $levelPlayerOne, int $againstLevelPlayerTwo)
    {
        return 1/(1+(10 ** (($againstLevelPlayerTwo - $levelPlayerOne)/400)));
    }

    public static function setNewLevel(int &$levelPlayerOne, int $againstLevelPlayerTwo, int $playerOneResult)
    {
        if (!in_array($playerOneResult, self::RESULT_POSSIBILITIES)) {
            trigger_error(sprintf('Invalid result. Expected %s',implode(' or ', self::RESULT_POSSIBILITIES)));
        }

        $levelPlayerOne += (int) (32 * ($playerOneResult - self::probabilityAgainst($levelPlayerOne, $againstLevelPlayerTwo)));
    }

    public function getResultWinner() {
        return self::RESULT_WINNER;
    }

    public function getResultLoser() {
        return self::RESULT_LOSER;
    }
}

$greg = new  Player(400);
$jade = new Player(800);

$encounter = new Encounter;

echo sprintf(
    'Greg à %.2f%% chance de gagner face a Jade',
    $encounter->probabilityAgainst($greg->getLevel(), $jade->getLevel())*100
).PHP_EOL;

// Imaginons que greg l'emporte tout de même.
$encounter->setNewLevel($greg->level, $jade->level, $encounter->getResultWinner());
$encounter->setNewLevel($jade->level, $greg->level, $encounter->getResultLoser());

echo sprintf(
    'les niveaux des joueurs ont évolués vers %s pour Greg et %s pour Jade',
    $greg->getLevel(),
    $jade->getLevel()
);

exit(0);
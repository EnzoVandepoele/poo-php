<?php

declare(strict_types=1);

class Player {
    public int $level;
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

echo sprintf(
    'Greg à %.2f%% chance de gagner face a Jade',
    probabilityAgainst($greg, $jade)*100
).PHP_EOL;

// Imaginons que greg l'emporte tout de même.
setNewLevel($greg, $jade, RESULT_WINNER);
setNewLevel($jade, $greg, RESULT_LOSER);

echo sprintf(
    'les niveaux des joueurs ont évolués vers %s pour Greg et %s pour Jade',
    $greg,
    $jade
);

exit(0);
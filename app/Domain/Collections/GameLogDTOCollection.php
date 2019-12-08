<?php


namespace App\Domain\Collections;


use Illuminate\Support\Collection;

class GameLogDTOCollection extends Collection
{
    protected $gameOver = false;

    /**
     * @return bool
     */
    public function isGameOver(): bool
    {
        return $this->gameOver;
    }

    /**
     * @param bool $gameOver
     * @return $this
     */
    public function setGameOver(bool $gameOver)
    {
        $this->gameOver = $gameOver;
        return $this;
    }


}

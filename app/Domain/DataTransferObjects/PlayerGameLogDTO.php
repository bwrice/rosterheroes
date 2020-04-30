<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 5/7/19
 * Time: 8:05 PM
 */

namespace App\Domain\DataTransferObjects;


use App\Domain\Models\Game;
use App\Domain\Models\Player;
use App\Domain\Models\Team;
use Illuminate\Support\Collection;

class PlayerGameLogDTO
{
    /**
     * @var Player
     */
    protected $player;
    /**
     * @var Game
     */
    protected $game;
    /**
     * @var Team
     */
    protected $team;
    /**
     * @var Collection
     */
    protected $statAmountDTOs;

    public function __construct(
        Player $player,
        Game $game,
        Team $team,
        Collection $statAmountDTOs)
    {
        $this->player = $player;
        $this->game = $game;
        $this->team = $team;
        $this->statAmountDTOs = $statAmountDTOs;
    }

    /**
     * @return Player
     */
    public function getPlayer(): Player
    {
        return $this->player;
    }

    /**
     * @return Game
     */
    public function getGame(): Game
    {
        return $this->game;
    }

    /**
     * @return Team
     */
    public function getTeam(): Team
    {
        return $this->team;
    }

    /**
     * @return Collection
     */
    public function getStatAmountDTOs(): Collection
    {
        return $this->statAmountDTOs;
    }

    /**
     * @param Collection $statAmountDTOs
     * @return PlayerGameLogDTO
     */
    public function setStatAmountDTOs(Collection $statAmountDTOs): PlayerGameLogDTO
    {
        $this->statAmountDTOs = $statAmountDTOs;
        return $this;
    }
}

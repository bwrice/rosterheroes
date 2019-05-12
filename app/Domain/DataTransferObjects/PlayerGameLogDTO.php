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
use Illuminate\Database\Eloquent\Collection;

class PlayerGameLogDTO
{
    /**
     * @var string
     */
    private $externalID;
    /**
     * @var Player
     */
    private $player;
    /**
     * @var Game
     */
    private $game;
    /**
     * @var Team
     */
    private $team;
    /**
     * @var Collection
     */
    private $statAmountDTOs;

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
}
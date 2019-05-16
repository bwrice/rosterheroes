<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 5/14/19
 * Time: 10:14 PM
 */

namespace App\Domain\Actions;


use App\Domain\Models\Game;
use App\Domain\Models\Player;
use App\Domain\Models\Week;
use App\Exceptions\InvalidGameException;
use App\Exceptions\InvalidPlayerException;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;

class CreateWeeklyGamePlayer
{
    /**
     * @var Week
     */
    private $week;
    /**
     * @var Game
     */
    private $game;
    /**
     * @var Player
     */
    private $player;
    /**
     * @var Collection
     */
    private $playerGameLogs;

    public function __construct(Week $week, Game $game, Player $player, Collection $playerGameLogs)
    {
        $this->week = $week;
        $this->game = $game;
        $this->player = $player;
        $this->playerGameLogs = $playerGameLogs;
    }

    public function __invoke()
    {
        if ( ! $this->game->starts_at->isBetween($this->week->everything_locks_at, $this->week->ends_at)) {
            throw new InvalidGameException($this->game);
        }

        if ( ! $this->game->hasTeam($this->player->team) ) {
            throw new InvalidPlayerException($this->player);
        }

        if ( $this->player->positions->isEmpty() ) {
            throw new InvalidPlayerException($this->player);
        }

    }
}
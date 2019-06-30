<?php

namespace App\Jobs;

use App\Domain\Actions\CreatePlayerSpirit;
use App\Domain\Models\Game;
use App\Domain\Models\League;
use App\Domain\Models\Player;
use App\Domain\Models\Week;
use App\Exceptions\InvalidGameException;
use App\Exceptions\InvalidPlayerException;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CreatePlayerSpiritJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * @var Week
     */
    private $week;
    /**
     * @var League
     */
    private $league;
    /**
     * @var Game
     */
    private $game;
    /**
     * @var Player
     */
    private $player;

    public function __construct(Week $week, Game $game, Player $player)
    {
        $this->week = $week;
        $this->game = $game;
        $this->player = $player;
    }

    /**
     * @throws \Exception
     */
    public function handle()
    {
        if ( ! $this->game->starts_at->isBetween($this->week->everything_locks_at, $this->week->ends_at)) {
            throw new InvalidGameException($this->game);
        }

        if ( ! $this->game->hasTeam($this->player->team) ) {
            throw new InvalidPlayerException($this->player);
        }

        $position = $this->player->positions->withHighestPositionValue();
        if (!$position) {
            throw new InvalidPlayerException($this->player, $this->player->fullName() . " has zero positions");
        }

        $action = new CreatePlayerSpirit($this->week, $this->game, $this->player, $position);
        $action();
    }
}

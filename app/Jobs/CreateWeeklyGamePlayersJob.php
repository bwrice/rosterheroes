<?php

namespace App\Jobs;

use App\Domain\Actions\CreateWeeklyGamePlayer;
use App\Domain\Models\Game;
use App\Domain\Models\League;
use App\Domain\Models\Player;
use App\Domain\Models\Week;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateWeeklyGamePlayersJob implements ShouldQueue
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
        $action = new CreateWeeklyGamePlayer($this->week, $this->game, $this->player);
        $action();
    }
}

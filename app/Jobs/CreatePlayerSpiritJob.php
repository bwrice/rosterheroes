<?php

namespace App\Jobs;

use App\Domain\Actions\CreatePlayerSpiritAction;
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
     * @param CreatePlayerSpiritAction $createPlayerSpiritAction
     * @throws \MathPHP\Exception\BadDataException
     */
    public function handle(CreatePlayerSpiritAction $createPlayerSpiritAction)
    {
        $createPlayerSpiritAction->execute($this->week, $this->game, $this->player);
    }
}

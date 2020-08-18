<?php

namespace App\Jobs;

use App\Domain\Actions\CreateSpiritsForGame;
use App\Domain\Models\Game;
use App\Domain\Models\Week;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateSpiritsForGameJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Game
     */
    public $game;
    /**
     * @var Week
     */
    public $week;

    public function __construct(Game $game, Week $week)
    {
        $this->game = $game;
        $this->week = $week;
    }

    public function handle(CreateSpiritsForGame $domainAction)
    {
        $domainAction->execute($this->game, $this->week);
    }
}

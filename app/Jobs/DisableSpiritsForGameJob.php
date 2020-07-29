<?php

namespace App\Jobs;

use App\Domain\Actions\DisableSpiritsForGame;
use App\Domain\Models\Game;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DisableSpiritsForGameJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Game
     */
    public $game;

    public $reason;

    public function __construct(Game $game, $reason = 'N/A')
    {
        $this->game = $game;
        $this->reason = $reason;
    }

    /**
     * @param DisableSpiritsForGame $domainAction
     */
    public function handle(DisableSpiritsForGame $domainAction)
    {
        $domainAction->execute($this->game, $this->reason);
    }
}

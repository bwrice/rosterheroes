<?php

namespace App\Jobs;

use App\Domain\Actions\BuildPlayerGameLogsForGameAction;
use App\Domain\Models\Game;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdatePlayerGameLogsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 60;

    public $backoff = 30;

    public $tries = 3;


    /**
     * @var Game
     */
    private $game;
    /**
     * @var int
     */
    private $yearDelta;

    public function __construct(Game $game, int $yearDelta = 0)
    {
        $this->game = $game;
        $this->yearDelta = $yearDelta;
    }

    /**
     * @param BuildPlayerGameLogsForGameAction $domainAction
     */
    public function handle(BuildPlayerGameLogsForGameAction $domainAction)
    {
        $domainAction->execute($this->game, $this->yearDelta);
    }

    /**
     * @return Game
     */
    public function getGame(): Game
    {
        return $this->game;
    }

    /**
     * @return int
     */
    public function getYearDelta(): int
    {
        return $this->yearDelta;
    }
}

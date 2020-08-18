<?php

namespace App\Jobs;

use App\Domain\Actions\UpdateGames;
use App\Domain\DataTransferObjects\GameDTO;
use App\Domain\Models\Game;
use App\Domain\Models\League;
use App\External\Stats\StatsIntegration;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class UpdateGamesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var League
     */
    public $league;
    /**
     * @var int
     */
    public $yearDelta;
    /**
     * @var bool
     */
    public $regularSeason;

    public function __construct(League $league, int $yearDelta = 0, $regularSeason = true)
    {
        $this->league = $league;
        $this->yearDelta = $yearDelta;
        $this->regularSeason = $regularSeason;
    }

    public function handle(UpdateGames $domainAction)
    {
        $domainAction->execute($this->league, $this->yearDelta, $this->regularSeason);
    }
}

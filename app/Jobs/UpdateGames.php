<?php

namespace App\Jobs;

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

class UpdateGames implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * @param StatsIntegration $statsIntegration
     */
    public function handle(StatsIntegration $integration)
    {
        Log::notice("Beginning games udpate");

        $liveLeagues = League::all()->filter(function (League $league) {
            return $league->isLive();
        });

        $liveLeagues->each(function (League $league) use ($integration) {

            $gameDTOs = $integration->getGameDTOs($league);
            $gameDTOs->each(function (GameDTO $gameDTO) {
                Game::updateOrCreate([
                    'external_id' => $gameDTO->getExternalID()
                ], [
                    'league_id' => $gameDTO->getLeague()->id,
                    'name' => $gameDTO->getName(),
                    'location' => $gameDTO->getLocation(),
                    'abbreviation' => $gameDTO->getAbbreviation()
                ]);
            });
        });

        Log::notice("Finished updating games",  [
            'live_leagues' => $liveLeagues->pluck('name')->toArray()
        ]);
    }
}

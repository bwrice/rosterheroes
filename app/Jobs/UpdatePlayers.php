<?php

namespace App\Jobs;

use App\Domain\Players\Player;
use App\Domain\Players\PlayerDTO;
use App\External\Stats\StatsIntegration;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdatePlayers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @param StatsIntegration $integration
     *
     * @return void
     */
    public function handle(StatsIntegration $integration)
    {
        $teamDTOs = $integration->getPlayerDTOs();
        $teamDTOs->each(function (PlayerDTO $playerDTO) {
            /** @var Player $player */
            $player = Player::updateOrCreate([
                'external_id' => $playerDTO->getExternalID()
            ], [
                'team_id' => $playerDTO->getTeam()->id,
                'first_name' => $playerDTO->getFirstName(),
                'last_name' => $playerDTO->getLastName()
            ]);
            $player->positions()->syncWithoutDetaching($playerDTO->getPositions()->pluck('id')->toArray());
        });
    }
}

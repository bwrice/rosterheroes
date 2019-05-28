<?php

namespace App\Jobs;

use App\Domain\Models\League;
use App\Domain\Models\Player;
use App\Domain\DataTransferObjects\PlayerDTO;
use App\External\Stats\StatsIntegration;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Redis;

class UpdatePlayersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public const REDIS_THROTTLE_KEY = 'msf_update_players';

    /**
     * @var League
     */
    private $league;

    public function __construct(League $league)
    {
        $this->league = $league;
    }

    public function handle(StatsIntegration $statsIntegration)
    {

        // Game log API has rate limit of 1 request per 10 seconds, we add another 5 seconds for buffer
        Redis::throttle(self::REDIS_THROTTLE_KEY)->allow(10)->every(60)->then(function () use ($statsIntegration) {
            // Job logic...
            $this->performJob($statsIntegration);
        }, function () {
            // Could not obtain lock...

            return $this->release(10);
        });
    }

    /**
     * Execute the job.
     *
     * @param StatsIntegration $integration
     *
     * @return void
     */
    public function performJob(StatsIntegration $integration)
    {
        $playerDTOs = $integration->getPlayerDTOs($this->league);
        $playerDTOs->each(function (PlayerDTO $playerDTO) {
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

<?php

namespace App\Jobs;

use App\Domain\Models\League;
use App\Domain\Models\Player;
use App\Domain\DataTransferObjects\PlayerDTO;
use App\External\Stats\StatsIntegration;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
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
        try {
            $this->performJob($statsIntegration);
        } catch (ClientException $exception) {
            Log::debug("Client exception caught: " . $exception->getMessage());
            Log::debug("Releasing update players for league: " . $this->league->abbreviation);
            $this->release(60);
        }
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
        $integrationType = $integration->getIntegrationType();

        $count = 0;
        $playerDTOs = $integration->getPlayerDTOs($this->league);
        $playerDTOs->each(function (PlayerDTO $playerDTO) use ($integrationType, &$count) {

            $player = Player::query()->forIntegration($integrationType->id, $playerDTO->getExternalID())->first();

            if ($player) {
                // TODO: logging of player changes (especially name changes)
                $player->first_name = $playerDTO->getFirstName();
                $player->last_name = $playerDTO->getLastName();
                $player->team_id = $playerDTO->getTeam()->id;
                $player->save();
            } else {
                /** @var Player $player */
                $player = Player::query()->firstOrCreate([
                    'team_id' => $playerDTO->getTeam()->id,
                    'first_name' => $playerDTO->getFirstName(),
                    'last_name' => $playerDTO->getLastName()
                ]);

                $player->externalPlayers()->create([
                    'integration_type_id' => $integrationType->id,
                    'external_id' => $playerDTO->getExternalID()
                ]);
            }
            $player->positions()->syncWithoutDetaching($playerDTO->getPositions()->pluck('id')->toArray());
        });

        if ($count > 0) {
            Log::alert($count . " new players created for integration for league: " . $this->league->abbreviation);
        }
    }
}

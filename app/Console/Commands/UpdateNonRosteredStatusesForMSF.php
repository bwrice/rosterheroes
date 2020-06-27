<?php

namespace App\Console\Commands;

use App\Domain\Models\League;
use App\Domain\Models\Player;
use App\Domain\Models\StatsIntegrationType;
use App\External\Stats\MySportsFeed\MySportsFeed;
use App\External\Stats\MySportsFeed\PlayerAPI;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class UpdateNonRosteredStatusesForMSF extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'msf:update-non-active-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    /**
     * @var PlayerAPI
     */
    private $msfPlayerAPI;

    /**
     * UpdateNonRosteredStatusesForMSF constructor.
     * @param PlayerAPI $msfPlayerAPI
     */
    public function __construct(PlayerAPI $msfPlayerAPI)
    {
        parent::__construct();
        $this->msfPlayerAPI = $msfPlayerAPI;
    }

    /**
     *
     */
    public function handle()
    {
        League::all()->each(function (League $league) {

            $playerStatuses = [
                Player::STATUS_RETIRED => 'RETIRED',
                Player::STATUS_FREE_AGENT => 'UFA'
            ];

            if ($league->abbreviation === League::MLB) {
                $playerStatuses[Player::STATUS_MINORS] = 'ASSIGNED-TO-MINORS';
            }

            foreach($playerStatuses as $localStatus => $externalStatus) {

                $this->info("Handling status " . $localStatus . " for league " . $league->abbreviation);

                $limit = 200;
                $offset = 0;
                $queryArgs = [
                    'rosterstatus' => $externalStatus,
                    'limit' => $limit,
                    'offset' => 0
                ];

                $integrationTypeID = StatsIntegrationType::query()->where('name', '=', MySportsFeed::INTEGRATION_NAME)->first()->id;
                $updatedPlayersCount = 0;

                do {
                    $playerData = collect($this->msfPlayerAPI->getData($league, $queryArgs));
                    $externalPlayerIDS = $playerData->map(function ($playerData) {
                        return $playerData['player']['id'];
                    });

                    $count = Player::query()->whereHas('externalPlayers', function (Builder $builder) use ($externalPlayerIDS, $integrationTypeID) {
                        return $builder->whereIn('external_id', $externalPlayerIDS->values()->toArray())->where('integration_type_id', '=', $integrationTypeID);
                    })->update([
                        'status' => $localStatus
                    ]);

                    $offset += $limit;
                    $queryArgs['offset'] = $offset;
                    $updatedPlayersCount += $count;
                    $continue = $playerData->count() === $limit;
                } while ($continue);

                $this->info($updatedPlayersCount . " with a status of " . $externalStatus . " updated");
            }
        });
    }
}

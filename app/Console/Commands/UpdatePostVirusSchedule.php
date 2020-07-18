<?php

namespace App\Console\Commands;

use App\Domain\DataTransferObjects\GameDTO;
use App\Domain\Models\ExternalGame;
use App\Domain\Models\Game;
use App\Domain\Models\League;
use App\External\Stats\MySportsFeed\MySportsFeed;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Date;

class UpdatePostVirusSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rh:post-virus';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update post COVID sports schedule for MLB and NBA';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param MySportsFeed $statsIntegration
     * @return bool
     */
    public function handle(MySportsFeed $statsIntegration)
    {
        if (now()->isAfter(Date::create(2020, 7, 25))) {
            $this->info('Nothing processed!');
            $this->info('Command Should only be used before the post COVID season starts');
            return false;
        }
        $newScheduleDate = Date::create(2020, 7, 20, 6);

        $externalGamesCount = ExternalGame::query()->whereHas('game', function (Builder $builder) use ($newScheduleDate) {
            $builder->where('starts_at', '>=', $newScheduleDate);
        })->delete();

        $this->info($externalGamesCount . ' external games deleted');

        $gamesCount = Game::query()->where('starts_at', '>=', $newScheduleDate)->delete();

        $this->info($gamesCount . ' games deleted');

        $leagues = League::query()->whereIn('abbreviation', [League::MLB, League::NBA, League::NFL])->get();

        foreach($leagues as $league) {

            /** @var League $league */

            $this->info("Starting league " . $league->abbreviation);

            $yearDelta = $league->abbreviation === League::NBA ? -1 : 0;

            /** @var League $league */
            $gameDTOs = $statsIntegration->getGameDTOs($league, $yearDelta);
            $integrationType = $statsIntegration->getIntegrationType();
            $gameDTOs->each(function (GameDTO $gameDTO) use ($integrationType) {

                $game = Game::query()->forIntegration($integrationType->id, $gameDTO->getExternalID())->first();

                if ($game) {
                    $this->updateGame($game, $gameDTO);

                } else {
                    /** @var Game $game */
                    $game = Game::query()->create([
                        'starts_at' => $gameDTO->getStartsAt(),
                        'home_team_id' => $gameDTO->getHomeTeam()->id,
                        'away_team_id' => $gameDTO->getAwayTeam()->id,
                    ]);

                    $game->externalGames()->create([
                        'integration_type_id' => $integrationType->id,
                        'external_id' => $gameDTO->getExternalID()
                    ]);
                }
            });

            $this->info("Finished league " . $league->abbreviation);
        }
        return true;
    }

    protected function updateGame(Game $game, GameDTO $gameDTO)
    {
        if ($game->starts_at->timestamp !== $gameDTO->getStartsAt()->timestamp) {
            $game->starts_at = $gameDTO->getStartsAt();
            $game->save();
        }

        if ($game->schedule_status !== $gameDTO->getStatus()) {
            $game->schedule_status = $gameDTO->getStatus();
            $game->save();
        }

        return $game;
    }
}

<?php


namespace App\Domain\Actions;


use App\Domain\DataTransferObjects\PlayerGameLogDTO;
use App\Domain\DataTransferObjects\StatAmountDTO;
use App\Domain\Models\Game;
use App\Domain\Models\PlayerGameLog;
use App\External\Stats\StatsIntegration;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class BuildPlayerGameLogsForGameAction
{
    /**
     * @var StatsIntegration
     */
    private $statsIntegration;

    public function __construct(StatsIntegration $statsIntegration)
    {
        $this->statsIntegration = $statsIntegration;
    }

    public function execute(Game $game, int $yearDelta = 0)
    {
        if ( $yearDelta > 0 ) {
            throw new \RuntimeException("Year delta must be negative, " . $yearDelta . " was passed");
        }

        $gameLogDTOs = $this->statsIntegration->getGameLogDTOs($game, $yearDelta);

        DB::transaction(function () use ($gameLogDTOs, $game) {
            
            $gameLogDTOs->each(function (PlayerGameLogDTO $dto) {

                $playerID = $dto->getPlayer()->id;
                $gameID = $dto->getGame()->id;
                $teamID = $dto->getTeam()->id;

                /** @var PlayerGameLog $playerGameLog */
                $playerGameLog = PlayerGameLog::query()->firstOrCreate([
                    'player_id' => $playerID,
                    'game_id' => $gameID,
                    'team_id' => $teamID
                ]);

                $dto->getStatAmountDTOs()->each(function (StatAmountDTO $statAmountDTO) use ($playerGameLog) {
                    $playerGameLog->playerStats()->updateOrCreate([
                        'stat_type_id' => $statAmountDTO->getStatType()->id],
                        [
                            'amount' => $statAmountDTO->getAmount()
                        ]);
                });
            });

            if ($gameLogDTOs->isGameOver())  {
                $game->finalized_at = Date::now();
                $game->save();
            }
        });
    }
}

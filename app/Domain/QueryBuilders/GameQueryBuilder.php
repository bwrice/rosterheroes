<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 5/20/19
 * Time: 9:13 PM
 */

namespace App\Domain\QueryBuilders;


use App\Domain\Models\Game;
use App\Domain\Models\Week;
use App\Facades\CurrentWeek;
use App\Facades\WeekService;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class GameQueryBuilder
 * @package App\Domain\QueryBuilders
 *
 * @method  Game|object|static|null first($columns = ['*'])
 */
class GameQueryBuilder extends Builder
{

    /**
     * @param string $externalID
     * @return GameQueryBuilder
     */
    public function externalID(string $externalID)
    {
        return $this->where('external_id', '=', $externalID);
    }

    /**
     * @param CarbonPeriod $period
     * @return GameQueryBuilder
     */
    public function withinPeriod(CarbonPeriod $period)
    {
        return $this->whereBetween('starts_at',[
            $period->getStartDate(),
            $period->getEndDate()
        ]);
    }

    public function forIntegration(int $integrationTypeID, string $externalID)
    {
        return $this->whereHas('externalGames', function (Builder $builder) use ($integrationTypeID, $externalID) {
            return $builder->where('integration_type_id', '=', $integrationTypeID)
                ->where('external_id', '=', $externalID);
        });
    }

    public function forLeagues(array $leagueIDs)
    {
        return $this->whereHas('homeTeam', function (Builder $builder) use ($leagueIDs) {
            return $builder->whereIn('league_id', $leagueIDs);
        });
    }

    public function withPlayerSpiritsForWeeks(array $weekIDs)
    {
        return $this->whereHas('playerGameLogs', function (Builder $builder) use ($weekIDs) {
            return $builder->whereHas('playerSpirit', function (PlayerSpiritQueryBuilder $builder) use ($weekIDs) {
                return $builder->forWeeks($weekIDs);
            });
        });
    }

    public function isFinalized($finalized = true)
    {
        if ($finalized) {
            return $this->whereNotNull('finalized_at');
        }
        return $this->whereNull('finalized_at');
    }

    public function validForWeek(Week $week)
    {
        return $this->withinPeriod(WeekService::getValidGamePeriod($week->adventuring_locks_at));
    }

    public function validForCurrentWeek()
    {
        return $this->withinPeriod(CurrentWeek::validGamePeriod());
    }
}

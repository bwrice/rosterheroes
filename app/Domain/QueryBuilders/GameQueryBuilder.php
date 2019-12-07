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
}

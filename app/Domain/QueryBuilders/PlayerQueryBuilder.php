<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 5/20/19
 * Time: 9:13 PM
 */

namespace App\Domain\QueryBuilders;


use App\Domain\Collections\PlayerCollection;
use App\Domain\Collections\TeamCollection;
use App\Domain\Interfaces\PositionQueryable;
use App\Domain\Models\Game;
use App\Domain\Models\Player;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class PlayerQueryBuilder
 * @package App\Domain\QueryBuilders
 *
 * @method Player|object|static|null first($columns = ['*'])
 * @method PlayerCollection get($columns = ['*'])
 */
class PlayerQueryBuilder extends Builder implements PositionQueryable
{

    public function withPositions(array $positions): Builder
    {
        return $this->whereHas('positions', function (Builder $builder) use ($positions) {
            return $builder->whereIn('name', $positions);
        });
    }

    public function forIntegration(int $integrationTypeID, $externalIDs)
    {
        $externalIDs = (array) $externalIDs;
        return $this->whereHas('externalPlayers', function (Builder $builder) use ($integrationTypeID, $externalIDs) {
            return $builder->where('integration_type_id', '=', $integrationTypeID)
                ->whereIn('external_id', $externalIDs);
        });
    }

    public function withNoSpiritForGame(Game $game)
    {
        return $this->forTeams([
            $game->home_team_id,
            $game->away_team_id
        ])->whereDoesntHave('playerSpirits', function (PlayerSpiritQueryBuilder $builder) use ($game) {
            return $builder->where('game_id', '=', $game->id);
        });
    }

    public function forTeams(array $teamIDs)
    {
        return $this->whereIn('team_id', $this);
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 6/23/19
 * Time: 5:18 PM
 */

namespace App\Domain\QueryBuilders;

use App\Domain\Models\Game;
use App\Domain\Models\HeroRace;
use App\Domain\Models\Player;
use App\Domain\Models\Squad;
use App\Domain\Models\Week;
use App\Domain\Models\PlayerSpirit;
use App\Facades\CurrentWeek;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class PlayerSpiritQueryBuilder
 * @package App\Domain\QueryBuilders
 *
 * @method  PlayerSpirit|object|static|null first($columns = ['*'])
 */
class PlayerSpiritQueryBuilder extends Builder
{
    /**
     * @param Week|null $week
     * @return PlayerSpiritQueryBuilder
     */
    public function forWeek(Week $week)
    {
        return $this->where('week_id', '=', $week->id);
    }

    public function forCurrentWeek()
    {
        return $this->forWeek(CurrentWeek::get());
    }

    public function forWeeks(array $weekIDs)
    {
        return $this->whereIn('week_id', $weekIDs);
    }

    public function withPositions(array $positionNames)
    {
        return $this->whereHas('playerGameLog', function (Builder $builder) use ($positionNames) {
            return $builder->whereHas('player', function (PlayerQueryBuilder $builder) use ($positionNames) {
                return $builder->withPositions($positionNames);
            });
        });
    }

    public function forHeroRace(string $heroRaceName)
    {
        /** @var HeroRace $heroRace */
        $heroRace = HeroRace::query()->where('name','=', $heroRaceName)->first();
        $positionNames = $heroRace->positions->pluck('name')->toArray();
        return $this->withPositions($positionNames);
    }

    public function minEssenceCost(int $amount)
    {
        return $this->where('essence_cost', '>=', $amount);
    }

    public function maxEssenceCost(int $amount)
    {
        return $this->where('essence_cost', '<=', $amount);
    }

    public function withHeroes()
    {
        return $this->whereHas('heroes');
    }

    /**
     * @param Squad $squad
     * @return PlayerSpiritQueryBuilder
     */
    public function availableForSquad(Squad $squad)
    {
        return $this->whereDoesntHave('heroes', function (Builder $builder) use ($squad) {
            return $builder->where('squad_id', '=', $squad->id);
        });
    }

    public function forGame(Game $game)
    {
        return $this->whereHas('playerGameLog', function (Builder $builder) use ($game) {
            return $builder->where('game_id', '=', $game->id);
        });
    }
}

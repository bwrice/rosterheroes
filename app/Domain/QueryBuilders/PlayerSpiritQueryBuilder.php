<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 6/23/19
 * Time: 5:18 PM
 */

namespace App\Domain\QueryBuilders;


use App\Domain\Interfaces\HeroRaceQueryable;
use App\Domain\Interfaces\PositionQueryable;
use App\Domain\Interfaces\EssenceCostQueryable;
use App\Domain\Models\HeroRace;
use App\Domain\Models\Week;
use App\Domain\Models\PlayerSpirit;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class PlayerSpiritQueryBuilder
 * @package App\Domain\QueryBuilders
 *
 * @method  PlayerSpirit|object|static|null first($columns = ['*'])
 */
class PlayerSpiritQueryBuilder extends Builder implements PositionQueryable, EssenceCostQueryable, HeroRaceQueryable
{
    /**
     * @param Week|null $week
     * @return PlayerSpiritQueryBuilder
     */
    public function forWeek(Week $week = null)
    {
        $week = $week ?? Week::current();
        return $this->where('week_id', '=', $week->id);
    }

    public function withPositions(array $positions): Builder
    {
        return $this->whereHas('player', function (PlayerQueryBuilder $builder) use ($positions) {
            return $builder->withPositions($positions);
        });
    }

    public function forHeroRace(string $heroRaceName): Builder
    {
        /** @var HeroRace $heroRace */
        $heroRace = HeroRace::query()->where('name','=', $heroRaceName)->first();
        $positionNames = $heroRace->positions->pluck('name')->toArray();
        return $this->withPositions($positionNames);
    }

    public function minEssenceCost(int $amount): Builder
    {
        return $this->where('essence_cost', '>=', $amount);
    }

    public function maxEssenceCost(int $amount): Builder
    {
        return $this->where('essence_cost', '<=', $amount);
    }
}
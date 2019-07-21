<?php


namespace App\Domain\QueryBuilders\Filters;


use App\Domain\Interfaces\HeroRaceQueryable;
use App\Domain\Interfaces\PositionQueryable;
use App\Exceptions\InvalidFilterBuilderException;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class HeroRaceFilter implements Filter
{

    public function __invoke(Builder $query, $value, string $property): Builder
    {
        $model = $query->getModel();
        $subQuery = $model->newEloquentBuilder($query->getQuery())->setModel($model);
        if (! ($subQuery instanceof HeroRaceQueryable)) {
            throw new InvalidFilterBuilderException(static::class, HeroRaceQueryable::class, $subQuery);
        }
        return $subQuery->forHeroRace($value);
    }
}
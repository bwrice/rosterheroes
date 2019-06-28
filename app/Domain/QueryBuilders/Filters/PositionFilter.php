<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 6/24/19
 * Time: 10:55 PM
 */

namespace App\Domain\QueryBuilders\Filters;


use App\Domain\Interfaces\PositionQueryable;
use App\Exceptions\InvalidFilterBuilderException;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class PositionFilter implements Filter
{

    public function __invoke(Builder $builder, $value, string $property): Builder
    {
        $model = $builder->getModel();
        $subQuery = $model->newEloquentBuilder($builder->getQuery())->setModel($model);
        if (! ($subQuery instanceof PositionQueryable)) {
            throw new InvalidFilterBuilderException(static::class, PositionQueryable::class, $subQuery);
        }
        return $subQuery->withPositions((array) $value);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 5/20/19
 * Time: 9:13 PM
 */

namespace App\Domain\QueryBuilders;


use App\Domain\Interfaces\PositionQueryable;
use App\Domain\Models\Player;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class PlayerQueryBuilder
 * @package App\Domain\QueryBuilders
 *
 * @method Player|object|static|null first($columns = ['*'])
 */
class PlayerQueryBuilder extends Builder implements PositionQueryable
{
    /**
     * @param string $externalID
     * @return PlayerQueryBuilder
     */
    public function externalID(string $externalID)
    {
        return $this->where('external_id', '=', $externalID);
    }

    public function withPositions(array $positions): Builder
    {
        return $this->whereHas('positions', function (Builder $builder) use ($positions) {
            return $builder->whereIn('name', $positions);
        });
    }
}
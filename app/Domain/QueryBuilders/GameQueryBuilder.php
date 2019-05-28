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
     * @param Week $week
     * @return GameQueryBuilder
     */
    public function validForWeek(Week $week)
    {
        return $this->whereBetween('starts_at', [$week->everything_locks_at, $week->ends_at]);
    }
}
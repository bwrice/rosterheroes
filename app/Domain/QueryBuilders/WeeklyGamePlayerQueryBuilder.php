<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 6/23/19
 * Time: 5:18 PM
 */

namespace App\Domain\QueryBuilders;


use App\Domain\Models\Week;
use App\Domain\Models\WeeklyGamePlayer;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class WeeklyGamePlayerQueryBuilder
 * @package App\Domain\QueryBuilders
 *
 * @method  WeeklyGamePlayer|object|static|null first($columns = ['*'])
 */
class WeeklyGamePlayerQueryBuilder extends Builder
{
    /**
     * @param Week|null $week
     * @return WeeklyGamePlayerQueryBuilder
     */
    public function forWeek(Week $week = null)
    {
        $week = $week ?? Week::current();
        return $this->where('week_id', '=', $week->id);
    }
}
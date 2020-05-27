<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 3/30/19
 * Time: 10:03 PM
 */

namespace App\Domain\QueryBuilders;


use App\Domain\Models\Week;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class WeekQueryBuilder
 * @package App\Domain\QueryBuilders
 *
 * @method Week|object|static|null first($columns = ['*'])
 */
class WeekQueryBuilder extends Builder
{
    /**
     * @return Week|null
     */
    public function current()
    {
        $now = now();
        return $this->whereNotNull('made_current_at')
            ->orderByDesc('made_current_at')
            ->where('made_current_at', '<=', $now)
            ->first();
    }
}

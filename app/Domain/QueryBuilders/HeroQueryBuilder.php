<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 7/4/19
 * Time: 6:40 PM
 */

namespace App\Domain\QueryBuilders;


use App\Domain\Models\Squad;
use Illuminate\Database\Eloquent\Builder;

class HeroQueryBuilder extends Builder
{
    public function hasPlayerSpirit()
    {
        return $this->whereHas('playerSpirit');
    }

    public function amongSquad(Squad $squad)
    {
        return $this->whereHas('heroPost', function(Builder $builder) use ($squad) {
            return $builder->where('squad_id', '=', $squad->id);
        });
    }
}

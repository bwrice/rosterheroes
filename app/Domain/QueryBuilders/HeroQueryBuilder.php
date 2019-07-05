<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 7/4/19
 * Time: 6:40 PM
 */

namespace App\Domain\QueryBuilders;


use Illuminate\Database\Eloquent\Builder;

class HeroQueryBuilder extends Builder
{
    public function hasPlayerSpirit()
    {
        return $this->whereHas('playerSpirit');
    }
}
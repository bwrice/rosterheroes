<?php


namespace App\Domain\QueryBuilders;


use Illuminate\Database\Eloquent\Builder;

class SquadQueryBuilder extends Builder
{
    public function withPlayerSpirits()
    {
        return $this->whereHas('heroes', function (HeroQueryBuilder $builder) {
            return $builder->hasPlayerSpirit();
        });
    }
}

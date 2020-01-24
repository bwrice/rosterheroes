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

    public function isTest()
    {
        return $this->where('name', 'like', 'TestSquad%')
            ->whereHas('user', function (Builder $builder) {
                return $builder->where('email', 'like', '%@test.com');
        });
    }
}

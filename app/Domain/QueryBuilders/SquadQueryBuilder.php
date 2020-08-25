<?php


namespace App\Domain\QueryBuilders;


use App\Facades\NPC;
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

    public function npc()
    {
        return $this->whereHas('user', function (Builder $builder) {
            $builder->where('email', '=', NPC::user()->email);
        });
    }
}

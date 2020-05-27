<?php

namespace App\Aggregates;

use App\Domain\Models\Minion;
use App\StorableEvents\HeroBlocksSideQuestMinion;
use App\StorableEvents\HeroDealsDamageToSideQuestMinion;
use App\StorableEvents\HeroKillsSideQuestMinion;
use App\StorableEvents\HeroTakesDamageFromSideQuestMinion;
use App\StorableEvents\SideQuestMinionKillsHero;
use Spatie\EventSourcing\AggregateRoot;

final class HeroAggregate extends AggregateRoot
{

    public function dealDamageToSideQuestMinion(int $damage, Minion $minion)
    {
        $this->recordThat(new HeroDealsDamageToSideQuestMinion($damage, $minion));
        return $this;
    }

    public function killsSideQuestMinion(Minion $minion)
    {
        $this->recordThat(new HeroKillsSideQuestMinion($minion));
        return $this;
    }

    public function takesDamageFromSideQuestMinion(int $damage, Minion $minion)
    {
        $this->recordThat(new HeroTakesDamageFromSideQuestMinion($damage, $minion));
        return $this;
    }

    public function killedBySideQuestMinion(Minion $minion)
    {
        $this->recordThat(new SideQuestMinionKillsHero($minion));
        return $this;
    }

    public function blocksSideQuestMinion(Minion $minion)
    {
        $this->recordThat(new HeroBlocksSideQuestMinion($minion));
        return $this;
    }
}

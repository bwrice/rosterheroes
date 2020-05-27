<?php

namespace App\Aggregates;

use App\Domain\Models\Minion;
use App\StorableEvents\ItemDamagesSideQuestMinion;
use App\StorableEvents\ItemKillsSideQuestMinion;
use Spatie\EventSourcing\AggregateRoot;

final class ItemAggregate extends AggregateRoot
{

    public function damagesSideQuestMinion(int $damage, Minion $minion)
    {
        $this->recordThat(new ItemDamagesSideQuestMinion($damage, $minion));
        return $this;
    }

    public function killsSideQuestMinion(Minion $minion)
    {
        $this->recordThat(new ItemKillsSideQuestMinion($minion));
        return $this;
    }
}

<?php

namespace App\Aggregates;

use App\Domain\Models\Minion;
use App\StorableEvents\AttackAttachedToItem;
use App\StorableEvents\ItemCreated;
use App\StorableEvents\EnchantmentAttachedToItem;
use App\StorableEvents\ItemDamagesSideQuestMinion;
use App\StorableEvents\ItemKillsSideQuestMinion;
use Spatie\EventSourcing\AggregateRoot;

final class ItemAggregate extends AggregateRoot
{
    public function createItem(int $itemClassID, int $itemTypeID, int $materialTypeID, int $itemBlueprintID, string $name = null)
    {
        $this->recordThat(new ItemCreated($itemClassID, $itemTypeID, $materialTypeID, $itemBlueprintID, $name));

        return $this;
    }

    public function attachEnchantment(int $enchantmentID)
    {
        $this->recordThat(new EnchantmentAttachedToItem($enchantmentID));

        return $this;
    }

    public function attachAttack(int $attackID)
    {
        $this->recordThat(new AttackAttachedToItem($attackID));

        return $this;
    }

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

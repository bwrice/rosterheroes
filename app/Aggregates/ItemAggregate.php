<?php

namespace App\Aggregates;

use App\Domain\Models\Minion;
use App\StorableEvents\AttackAttachedToItem;
use App\StorableEvents\ItemCreated;
use App\StorableEvents\EnchantmentAttachedToItem;
use App\StorableEvents\ItemDamagesMinion;
use App\StorableEvents\ItemKillsMinion;
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

    public function damageMinion(int $damage, Minion $minion)
    {
        $this->recordThat(new ItemDamagesMinion($damage, $minion));
        return $this;
    }

    public function killMinion(Minion $minion)
    {
        $this->recordThat(new ItemKillsMinion($minion));
        return $this;
    }
}

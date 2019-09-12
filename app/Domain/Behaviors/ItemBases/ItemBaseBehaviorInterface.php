<?php

namespace App\Domain\Behaviors\ItemBases;

use App\Domain\Interfaces\UsesItems;

interface ItemBaseBehaviorInterface
{
    public function getSlotsCount(): int;

    public function getGroupName(): string;

    public function getSlotTypeIDs(): array;

    public function getCombatSpeedModifier(UsesItems $hasItems = null): float;

    public function getBaseDamageModifier(UsesItems $usesItems = null): float;

    public function getDamageMultiplierModifier(UsesItems $usesItems = null): float;
}

<?php

namespace App\Domain\Behaviors\ItemBases;

use App\Domain\Interfaces\HasItems;

interface ItemBaseBehaviorInterface
{
    public function getSlotsCount(): int;

    public function getGroupName(): string;

    public function getSlotTypeIDs(): array;

    public function getCombatSpeedModifier(HasItems $hasItems = null): float;

    public function getBaseDamageModifier(HasItems $hasItems = null): float;

    public function getDamageMultiplierModifier(HasItems $hasItems = null): float;
}

<?php

namespace App\Domain\Behaviors\ItemBases;

use App\Domain\Interfaces\UsesItems;

interface ItemBaseBehaviorInterface
{
    public function getSlotsCount(): int;

    public function getGroupName(): string;

    public function getSlotTypeNames(): array;

    public function getSlotTypeIDs(): array;

    public function getCombatSpeedBonus(UsesItems $hasItems = null): float;

    public function getBaseDamageBonus(UsesItems $usesItems = null): float;

    public function getDamageMultiplierBonus(UsesItems $usesItems = null): float;

    public function getBurdenModifier(): float;

    public function getProtectionModifier(): float;

    public function getBlockChanceModifier(): float;
}

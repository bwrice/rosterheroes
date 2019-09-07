<?php


namespace App\Domain\Behaviors\ItemBases;


use App\Domain\Interfaces\AdjustsBaseDamage;
use App\Domain\Interfaces\AdjustsCombatSpeed;
use App\Domain\Interfaces\AdjustsDamageModifier;

interface ItemBaseBehaviorInterface extends AdjustsBaseDamage, AdjustsDamageModifier, AdjustsCombatSpeed
{
    public function getSlotsCount(): int;

    public function getGroupName(): string;

    public function getSlotTypeIDs(): array;
}

<?php


namespace App\Domain\Behaviors\ItemBase;

use App\Domain\Behaviors\ItemGroup\ShieldGroup;
use App\Domain\Models\SlotType;

abstract class ShieldGroupBehavior extends ItemBaseBehavior
{
    public function __construct(ShieldGroup $shieldGroup)
    {
        parent::__construct($shieldGroup);
    }

    public function getSlotTypeNames(): array
    {
        return [
            SlotType::LEFT_ARM
        ];
    }
}

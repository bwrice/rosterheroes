<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/18/18
 * Time: 9:17 PM
 */

namespace App\Domain\Behaviors\ItemBases;

use App\Domain\Behaviors\ItemGroup\ItemGroupInterface;
use App\Domain\Models\SlotType;

abstract class ItemBaseBehavior implements ItemBaseBehaviorInterface
{
    /**
     * @var ItemGroupInterface
     */
    private $itemGroup;

    protected $weightMultiplier = 1;

    public function __construct(ItemGroupInterface $itemGroup)
    {
        $this->itemGroup = $itemGroup;
    }

    public function getGroupName(): string
    {
        return $this->itemGroup->name();
    }

    abstract public function getSlotTypeNames(): array;

    public function getSlotTypeIDs(): array
    {
        $slotTypes = $this->getSlotTypeNames();
        $slotTypes[] = SlotType::UNIVERSAL;

        return SlotType::query()
            ->whereIn('name', $slotTypes)
            ->pluck('id')
            ->toArray();
    }

    public function getWeightMultiplier(): float
    {
        return $this->weightMultiplier;
    }
}

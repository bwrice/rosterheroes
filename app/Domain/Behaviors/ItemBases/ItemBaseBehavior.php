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

    protected $weightModifier = 1;
    protected $protectionModifier = 1;
    protected $blockChanceModifier = 1;
    protected $resourceCostAmountModifier = 1;
    protected $resourceCostPercentModifier = 1;

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

    public function getBurdenModifier(): float
    {
        return $this->weightModifier;
    }

    public function getProtectionModifier(): float
    {
        return $this->protectionModifier;
    }

    public function getBlockChanceModifier(): float
    {
        return $this->blockChanceModifier;
    }

    public function getResourceCostAmountModifier(): float
    {
        return $this->resourceCostAmountModifier;
    }

    public function getResourceCostPercentModifier(): float
    {
        return $this->resourceCostPercentModifier;
    }
}

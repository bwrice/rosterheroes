<?php


namespace App\Domain\Actions\NPC;


use App\Domain\Models\Hero;
use App\Domain\Models\HeroClass;
use App\Domain\Models\Item;
use App\Domain\Models\ItemBase;
use App\Domain\Models\Support\GearSlots\GearSlot;
use Illuminate\Support\Collection;

class FindItemsForHeroToEquip
{
    public const PRIMARY_ARM_ITEM_BASES = [
        HeroClass::WARRIOR => [
            ItemBase::SWORD,
            ItemBase::AXE,
            ItemBase::MACE
        ],
        HeroClass::RANGER => [
            ItemBase::BOW,
            ItemBase::CROSSBOW,
        ],
        HeroClass::SORCERER => [
            ItemBase::STAFF,
            ItemBase::ORB
        ]
    ];

    /** @var Collection */
    protected $itemsToEquip;

    /** @var Hero */
    protected $hero;

    /** @var Collection */
    protected $gearSlots;

    /** @var Collection */
    protected $wagonItems;

    /** @var Collection */
    protected $exclude;

    /**
     * @param Hero $hero
     * @return Collection
     */
    public function execute(Hero $hero)
    {
        $this->itemsToEquip = collect();
        $this->exclude = collect();
        $this->hero = $hero;
        $this->gearSlots = $hero->getGearSlots();
        $this->wagonItems = $hero->squad->items()->with(Item::resourceRelations())->get();

        // eager-load hero items with needed relationships
        $hero->items()->with(Item::resourceRelations());
        $this->addPrimaryArmItem();
        $this->addOffArmItem();
        $this->addNonArmItems();

        return $this->itemsToEquip;
    }

    protected function addPrimaryArmItem()
    {
        /** @var GearSlot $primaryArmGearSlot */
        $primaryArmGearSlot = $this->hero->getGearSlots()->first(function (GearSlot $gearSlot) {
            return $gearSlot->getType() === GearSlot::PRIMARY_ARM;
        });

        $itemBaseNames = self::PRIMARY_ARM_ITEM_BASES[$this->hero->heroClass->name];
        $baseFilteredItems = $this->wagonItems->filter(function (Item $item) use ($itemBaseNames) {
            return in_array($item->itemType->itemBase->name, $itemBaseNames);
        });
        $this->findItemForGearSlot($baseFilteredItems, $primaryArmGearSlot);
    }

    protected function addOffArmItem()
    {
        if ($this->hero->heroClass->name === HeroClass::WARRIOR) {
            $shieldItems = $this->wagonItems->filter(fn (Item $item) => $item->itemType->itemBase->name === ItemBase::SHIELD);
            $offArmGearSlot = $this->gearSlots->filter(fn (GearSlot $gearSlot) => $gearSlot->getType() === GearSlot::OFF_ARM)->first();
            $this->findItemForGearSlot($shieldItems, $offArmGearSlot);
        }
    }

    protected function addNonArmItems()
    {
        $this->gearSlots->filter(function (GearSlot $gearSlot) {
            return ! in_array($gearSlot->getType(), [
                GearSlot::PRIMARY_ARM,
                GearSlot::OFF_ARM
            ]);
        })->each(function (GearSlot $gearSlot) {
            $this->findItemForGearSlot($this->wagonItems, $gearSlot);
        });
    }

    protected function findItemForGearSlot(Collection $items, GearSlot $gearSlot)
    {
        $itemToEquip = $items->reject(function (Item $item) {
            // reject those excluded
            return in_array($item->id, $this->exclude->pluck('id')->toArray());
        })->filter(function (Item $item) use ($gearSlot) {
            // filter by gear-type
            return in_array($gearSlot->getType(), $item->itemType->itemBase->getSlotTypeNames());
        })->sortByDesc(fn (Item $item) => $item->getValue())->first();

        if ($itemToEquip) {
            $this->itemsToEquip->push($itemToEquip);
            $this->exclude->push($itemToEquip);
        }
    }
}

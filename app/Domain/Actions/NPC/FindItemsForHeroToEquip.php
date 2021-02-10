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

    /**
     * @param Hero $hero
     * @return Collection
     */
    public function execute(Hero $hero)
    {
        $this->itemsToEquip = collect();
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
        $itemToEquip = $this->wagonItems->filter(function (Item $item) use ($itemBaseNames) {
            return in_array($item->itemType->itemBase->name, $itemBaseNames);
        })->sortByDesc(function (Item $item) {
            return $item->getValue();
        })->first();
        if ($itemToEquip) {
            $this->itemsToEquip->push($itemToEquip);
        }
    }

    protected function addOffArmItem()
    {
        if ($this->hero->heroClass->name === HeroClass::WARRIOR) {
            $shield = $this->wagonItems->filter(function (Item $item) {
                return $item->itemType->itemBase->name === ItemBase::SHIELD;
            })->sortByDesc(function (Item $item) {
                return $item->getValue();
            })->first();
            if ($shield) {
                $this->itemsToEquip->push($shield);
            }
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
            $itemToEquip = $this->wagonItems->filter(function (Item $item) use ($gearSlot) {
                return in_array($gearSlot->getType(), $item->itemType->itemBase->getSlotTypeNames());
            })->sortByDesc(function (Item $item) {
                return $item->getValue();
            })->first();
            if ($itemToEquip) {
                $this->itemsToEquip->push($itemToEquip);
            }
        });
    }
}

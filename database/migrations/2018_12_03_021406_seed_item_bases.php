<?php

use App\Domain\Models\ItemBase;
use App\Helpers\ModelNameSeeding\ModelNameSeederMigration;

class SeedItemBases extends ModelNameSeederMigration
{
    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return ItemBase::class;
    }

    /**
     * @return array
     */
    protected function getSeedNames(): array
    {
        return [
            ItemBase::DAGGER,
            ItemBase::SWORD,
            ItemBase::AXE,
            ItemBase::MACE,
            ItemBase::BOW,
            ItemBase::CROSSBOW,
            ItemBase::THROWING_WEAPON,
            ItemBase::POLE_ARM,
            ItemBase::TWO_HAND_SWORD,
            ItemBase::TWO_HAND_AXE,
            ItemBase::WAND,
            ItemBase::ORB,
            ItemBase::STAFF,
            ItemBase::PSIONIC_ONE_HAND,
            ItemBase::PSIONIC_TWO_HAND,
            ItemBase::SHIELD,
            ItemBase::PSIONIC_SHIELD,
            ItemBase::HELMET,
            ItemBase::CAP,
            ItemBase::EYE_WEAR,
            ItemBase::HEAVY_ARMOR,
            ItemBase::LIGHT_ARMOR,
            ItemBase::LEGGINGS,
            ItemBase::ROBES,
            ItemBase::GLOVES,
            ItemBase::GAUNTLETS,
            ItemBase::SHOES,
            ItemBase::BOOTS,
            ItemBase::BELT,
            ItemBase::SASH,
            ItemBase::NECKLACE,
            ItemBase::BRACELET,
            ItemBase::RING,
            ItemBase::CROWN
        ];
    }
}

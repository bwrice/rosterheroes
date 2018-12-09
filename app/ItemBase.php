<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemBase extends Model
{
    const DAGGER = 'dagger';
    const SWORD = 'sword';
    const AXE = 'axe';
    const MACE = 'mace';
    const BOW = 'bow';
    const CROSSBOW = 'crossbow';
    const THROWING_WEAPON = 'throwing-weapon';
    const POLE_ARM = 'pole-arm';
    const TWO_HAND_SWORD = 'two-hand-sword';
    const TWO_HAND_AXE = 'two-hand-axe';
    const WAND = 'wand';
    const ORB = 'orb';
    const STAFF = 'staff';
    const PSIONIC_ONE_HAND = 'psionic-one-hand';
    const PSIONIC_TWO_HAND = 'psionic-two-hand';
    const SHIELD = 'shield';
    const PSIONIC_SHIELD = 'psionic-shield';
    const HELMET = 'helmet';
    const CAP = 'cap';
    const EYE_WEAR = 'eye-wear';
    const HEAVY_ARMOR = 'heavy-armor';
    const LIGHT_ARMOR = 'light-armor';
    const ROBES = 'robes';
    const GLOVES = 'gloves';
    const GAUNTLETS = 'gauntlets';
    const SHOES = 'shoes';
    const BOOTS = 'boots';
    const BELT = 'belt';
    const SASH = 'sash';
    const NECKLACE = 'necklace';
    const BRACELET = 'bracelet';
    const RING = 'ring';
    const CROWN = 'crown';

    protected $guarded = [];


    public function slotTypes()
    {
        return $this->belongsToMany( SlotType::class )->withTimestamps();
    }

    public function measurableTypes()
    {
        return $this->belongsToMany(MeasurableType::class, 'item_base_measurable_type', 'item_base_id', 'type_id')->withTimestamps();
    }

    public function itemTypes()
    {
        return $this->hasMany(ItemType::class);
    }

    public function itemGroup()
    {
        return $this->belongsTo(ItemGroup::class);
    }
}

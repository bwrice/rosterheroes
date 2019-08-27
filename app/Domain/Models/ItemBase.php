<?php

namespace App\Domain\Models;

use App\Domain\Interfaces\ItemBehavior;
use App\Domain\Models\HeroRank;
use App\Domain\Models\ItemGroup;
use App\Domain\Behaviors\ItemBase\AxeBehavior;
use App\Domain\Behaviors\ItemBase\BeltBehavior;
use App\Domain\Behaviors\ItemBase\BootsBehavior;
use App\Domain\Behaviors\ItemBase\BowBehavior;
use App\Domain\Behaviors\ItemBase\BraceletBehavior;
use App\Domain\Behaviors\ItemBase\CapBehavior;
use App\Domain\Behaviors\ItemBase\CrossbowBehavior;
use App\Domain\Behaviors\ItemBase\CrownBehavior;
use App\Domain\Behaviors\ItemBase\DaggerBehavior;
use App\Domain\Behaviors\ItemBase\EyeWearBehavior;
use App\Domain\Behaviors\ItemBase\GauntletsBehavior;
use App\Domain\Behaviors\ItemBase\GlovesBehavior;
use App\Domain\Behaviors\ItemBase\HeavyArmorBehavior;
use App\Domain\Behaviors\ItemBase\HelmetBehavior;
use App\Domain\Behaviors\ItemBase\ItemBaseBehavior;
use App\Domain\Behaviors\ItemBase\LightArmorBehavior;
use App\Domain\Behaviors\ItemBase\MaceBehavior;
use App\Domain\Behaviors\ItemBase\NecklaceBehavior;
use App\Domain\Behaviors\ItemBase\OrbBehavior;
use App\Domain\Behaviors\ItemBase\PoleArmBehavior;
use App\Domain\Behaviors\ItemBase\PsionicOneHandBehavior;
use App\Domain\Behaviors\ItemBase\PsionicShieldBehavior;
use App\Domain\Behaviors\ItemBase\PsionicTwoHandBehavior;
use App\Domain\Behaviors\ItemBase\RingBehavior;
use App\Domain\Behaviors\ItemBase\RobesBehavior;
use App\Domain\Behaviors\ItemBase\SashBehavior;
use App\Domain\Behaviors\ItemBase\ShieldBehavior;
use App\Domain\Behaviors\ItemBase\ShoesBehavior;
use App\Domain\Behaviors\ItemBase\StaffBehavior;
use App\Domain\Behaviors\ItemBase\SwordBehavior;
use App\Domain\Behaviors\ItemBase\ThrowingWeaponBehavior;
use App\Domain\Behaviors\ItemBase\TwoHandAxeBehavior;
use App\Domain\Behaviors\ItemBase\TwoHandSwordBehavior;
use App\Domain\Behaviors\ItemBase\WandBehavior;
use App\Domain\Models\ItemType;
use App\Domain\Models\MeasurableType;
use App\Domain\Models\SlotType;
use App\Exceptions\UnknownBehaviorException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ItemBase
 * @package App
 *
 * @property int $id
 * @property string $name
 *
 * @property ItemGroup $itemGroup
 *
 * @property Collection $slotTypes
 */
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

    /**
     * @return int
     */
    public function getSlotsCount()
    {
        return $this->getBehavior()->getSlotsCount();
    }

    /**
     * @return ItemBehavior
     */
    public function getBehavior(): ItemBehavior
    {
        switch( $this->name ) {
            case self::DAGGER:
                return app(DaggerBehavior::class);
            case self::SWORD:
                return app(SwordBehavior::class);
            case self::AXE:
                return app(AxeBehavior::class);
            case self::MACE:
                return app(MaceBehavior::class);
            case self::BOW:
                return app(BowBehavior::class);
            case self::CROSSBOW:
                return app(CrossbowBehavior::class);
            case self::THROWING_WEAPON:
                return app(ThrowingWeaponBehavior::class);
            case self::POLE_ARM:
                return app(PoleArmBehavior::class);
            case self::TWO_HAND_SWORD:
                return app(TwoHandSwordBehavior::class);
            case self::TWO_HAND_AXE:
                return app(TwoHandAxeBehavior::class);
            case self::WAND:
                return app(WandBehavior::class);
            case self::ORB:
                return app(OrbBehavior::class);
            case self::STAFF:
                return app(StaffBehavior::class);
            case self::PSIONIC_ONE_HAND:
                return app(PsionicOneHandBehavior::class);
            case self::PSIONIC_TWO_HAND:
                return app(PsionicTwoHandBehavior::class);
            case self::SHIELD:
                return app(ShieldBehavior::class);
            case self::PSIONIC_SHIELD:
                return app(PsionicShieldBehavior::class);
            case self::HELMET:
                return app(HelmetBehavior::class);
            case self::CAP:
                return app(CapBehavior::class);
            case self::EYE_WEAR:
                return app(EyeWearBehavior::class);
            case self::HEAVY_ARMOR:
                return app(HeavyArmorBehavior::class);
            case self::LIGHT_ARMOR:
                return app(LightArmorBehavior::class);
            case self::ROBES:
                return app(RobesBehavior::class);
            case self::GLOVES:
                return app(GlovesBehavior::class);
            case self::GAUNTLETS:
                return app(GauntletsBehavior::class);
            case self::BOOTS:
                return app(BootsBehavior::class);
            case self::SHOES:
                return app(ShoesBehavior::class);
            case self::BELT:
                return app(BeltBehavior::class);
            case self::SASH:
                return app(SashBehavior::class);
            case self::NECKLACE:
                return app(NecklaceBehavior::class);
            case self::BRACELET:
                return app(BraceletBehavior::class);
            case self::RING:
                return app(RingBehavior::class);
            case self::CROWN:
                return app(CrownBehavior::class);
        }

        throw new UnknownBehaviorException($this->name, ItemBehavior::class);
    }
}

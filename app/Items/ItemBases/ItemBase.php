<?php

namespace App\Items\ItemBases;

use App\HeroRank;
use App\ItemGroup;
use App\Items\ItemBases\Behaviors\AxeBehavior;
use App\Items\ItemBases\Behaviors\BeltBehavior;
use App\Items\ItemBases\Behaviors\BootsBehavior;
use App\Items\ItemBases\Behaviors\BowBehavior;
use App\Items\ItemBases\Behaviors\BraceletBehavior;
use App\Items\ItemBases\Behaviors\CapBehavior;
use App\Items\ItemBases\Behaviors\CrossbowBehavior;
use App\Items\ItemBases\Behaviors\CrownBehavior;
use App\Items\ItemBases\Behaviors\DaggerBehavior;
use App\Items\ItemBases\Behaviors\EyeWearBehavior;
use App\Items\ItemBases\Behaviors\GauntletsBehavior;
use App\Items\ItemBases\Behaviors\GlovesBehavior;
use App\Items\ItemBases\Behaviors\HeavyArmorBehavior;
use App\Items\ItemBases\Behaviors\HelmetBehavior;
use App\Items\ItemBases\Behaviors\ItemBaseBehavior;
use App\Items\ItemBases\Behaviors\LightArmorBehavior;
use App\Items\ItemBases\Behaviors\MaceBehavior;
use App\Items\ItemBases\Behaviors\NecklaceBehavior;
use App\Items\ItemBases\Behaviors\OrbBehavior;
use App\Items\ItemBases\Behaviors\PoleArmBehavior;
use App\Items\ItemBases\Behaviors\PsionicOneHandBehavior;
use App\Items\ItemBases\Behaviors\PsionicShieldBehavior;
use App\Items\ItemBases\Behaviors\PsionicTwoHandBehavior;
use App\Items\ItemBases\Behaviors\RingBehavior;
use App\Items\ItemBases\Behaviors\RobesBehavior;
use App\Items\ItemBases\Behaviors\SashBehavior;
use App\Items\ItemBases\Behaviors\ShieldBehavior;
use App\Items\ItemBases\Behaviors\ShoesBehavior;
use App\Items\ItemBases\Behaviors\StaffBehavior;
use App\Items\ItemBases\Behaviors\SwordBehavior;
use App\Items\ItemBases\Behaviors\ThrowingWeaponBehavior;
use App\Items\ItemBases\Behaviors\TwoHandAxeBehavior;
use App\Items\ItemBases\Behaviors\TwoHandSwordBehavior;
use App\Items\ItemBases\Behaviors\WandBehavior;
use App\ItemType;
use App\MeasurableType;
use App\SlotType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ItemBase
 * @package App
 *
 * @property int $id
 * @property string $name
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
     * @return ItemBaseBehavior
     */
    public function getBehavior()
    {
        switch( $this->name ) {
            case self::DAGGER:
                return app()->make(DaggerBehavior::class);
            case self::SWORD:
                return app()->make(SwordBehavior::class);
            case self::AXE:
                return app()->make(AxeBehavior::class);
            case self::MACE:
                return app()->make(MaceBehavior::class);
            case self::BOW:
                return app()->make(BowBehavior::class);
            case self::CROSSBOW:
                return app()->make(CrossbowBehavior::class);
            case self::THROWING_WEAPON:
                return app()->make(ThrowingWeaponBehavior::class);
            case self::POLE_ARM:
                return app()->make(PoleArmBehavior::class);
            case self::TWO_HAND_SWORD:
                return app()->make(TwoHandSwordBehavior::class);
            case self::TWO_HAND_AXE:
                return app()->make(TwoHandAxeBehavior::class);
            case self::WAND:
                return app()->make(WandBehavior::class);
            case self::ORB:
                return app()->make(OrbBehavior::class);
            case self::STAFF:
                return app()->make(StaffBehavior::class);
            case self::PSIONIC_ONE_HAND:
                return app()->make(PsionicOneHandBehavior::class);
            case self::PSIONIC_TWO_HAND:
                return app()->make(PsionicTwoHandBehavior::class);
            case self::SHIELD:
                return app()->make(ShieldBehavior::class);
            case self::PSIONIC_SHIELD:
                return app()->make(PsionicShieldBehavior::class);
            case self::HELMET:
                return app()->make(HelmetBehavior::class);
            case self::CAP:
                return app()->make(CapBehavior::class);
            case self::EYE_WEAR:
                return app()->make(EyeWearBehavior::class);
            case self::HEAVY_ARMOR:
                return app()->make(HeavyArmorBehavior::class);
            case self::LIGHT_ARMOR:
                return app()->make(LightArmorBehavior::class);
            case self::ROBES:
                return app()->make(RobesBehavior::class);
            case self::GLOVES:
                return app()->make(GlovesBehavior::class);
            case self::GAUNTLETS:
                return app()->make(GauntletsBehavior::class);
            case self::BOOTS:
                return app()->make( BootsBehavior::class );
            case self::SHOES:
                return app()->make(ShoesBehavior::class);
            case self::BELT:
                return app()->make(BeltBehavior::class);
            case self::SASH:
                return app()->make(SashBehavior::class);
            case self::NECKLACE:
                return app()->make(NecklaceBehavior::class);
            case self::BRACELET:
                return app()->make(BraceletBehavior::class);
            case self::RING:
                return app()->make(RingBehavior::class);
            case self::CROWN:
                return app()->make(CrownBehavior::class);
        }

        throw new \RuntimeException("Unknown behavior for item base");
    }
}

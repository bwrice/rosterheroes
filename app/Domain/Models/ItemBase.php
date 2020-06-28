<?php

namespace App\Domain\Models;

use App\Domain\Behaviors\ItemBases\ItemBaseBehaviorInterface;
use App\Domain\Behaviors\ItemBases\Armor\LeggingsBehavior;
use App\Domain\Collections\AttackCollection;
use App\Domain\Models\HeroRank;
use App\Domain\Behaviors\ItemBases\Weapons\AxeBehavior;
use App\Domain\Behaviors\ItemBases\Armor\BeltBehavior;
use App\Domain\Behaviors\ItemBases\Armor\BootsBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\BowBehavior;
use App\Domain\Behaviors\ItemBases\Jewelry\BraceletBehavior;
use App\Domain\Behaviors\ItemBases\Clothing\CapBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\CrossbowBehavior;
use App\Domain\Behaviors\ItemBases\Jewelry\CrownBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\DaggerBehavior;
use App\Domain\Behaviors\ItemBases\Armor\GauntletsBehavior;
use App\Domain\Behaviors\ItemBases\Clothing\GlovesBehavior;
use App\Domain\Behaviors\ItemBases\Armor\HeavyArmorBehavior;
use App\Domain\Behaviors\ItemBases\Armor\HelmetBehavior;
use App\Domain\Behaviors\ItemBases\ItemBaseBehavior;
use App\Domain\Behaviors\ItemBases\Armor\LightArmorBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\MaceBehavior;
use App\Domain\Behaviors\ItemBases\Jewelry\NecklaceBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\OrbBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\PoleArmBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\PsionicOneHandBehavior;
use App\Domain\Behaviors\ItemBases\Shields\PsionicShieldBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\PsionicTwoHandBehavior;
use App\Domain\Behaviors\ItemBases\Jewelry\RingBehavior;
use App\Domain\Behaviors\ItemBases\Clothing\RobesBehavior;
use App\Domain\Behaviors\ItemBases\Clothing\SashBehavior;
use App\Domain\Behaviors\ItemBases\Shields\ShieldBehavior;
use App\Domain\Behaviors\ItemBases\Clothing\ShoesBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\StaffBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\SwordBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\ThrowingWeaponBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\TwoHandAxeBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\TwoHandSwordBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\WandBehavior;
use App\Domain\Models\ItemType;
use App\Domain\Models\MeasurableType;
use App\Domain\Models\SlotType;
use App\Domain\Models\Traits\HasUniqueNames;
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
 * @property Collection $itemTypes
 */
class ItemBase extends Model
{
    use HasUniqueNames;

    public const DAGGER = 'Dagger';
    public const SWORD = 'Sword';
    public const AXE = 'Axe';
    public const MACE = 'Mace';
    public const BOW = 'Bow';
    public const CROSSBOW = 'Crossbow';
    public const THROWING_WEAPON = 'Throwing Weapon';
    public const POLEARM = 'Polearm';
    public const TWO_HAND_SWORD = 'Two Hand Sword';
    public const TWO_HAND_AXE = 'Two Hand Axe';
    public const WAND = 'Wand';
    public const ORB = 'Orb';
    public const STAFF = 'Staff';
    public const PSIONIC_ONE_HAND = 'Psionic One Hand';
    public const PSIONIC_TWO_HAND = 'Psionic Two Hand';
    public const SHIELD = 'Shield';
    public const PSIONIC_SHIELD = 'Psionic Shield';
    public const HELMET = 'Helmet';
    public const CAP = 'Cap';
//    public const EYE_WEAR = 'eye-wear';
    public const HEAVY_ARMOR = 'Heavy Armor';
    public const LIGHT_ARMOR = 'Light Armor';
    public const LEGGINGS = 'Leggings';
    public const ROBES = 'Robes';
    public const GLOVES = 'Gloves';
    public const GAUNTLETS = 'Gauntlets';
    public const SHOES = 'Shoes';
    public const BOOTS = 'Boots';
    public const BELT = 'Belt';
    public const SASH = 'Sash';
    public const NECKLACE = 'Necklace';
    public const BRACELET = 'Bracelet';
    public const RING = 'Ring';
    public const CROWN = 'Crown';

    protected $guarded = [];

    public function measurableTypes()
    {
        return $this->belongsToMany(MeasurableType::class, 'item_base_measurable_type', 'item_base_id', 'type_id')->withTimestamps();
    }

    public function materialTypes()
    {
        return $this->belongsToMany(MaterialType::class)->withTimestamps();
    }

    public function itemTypes()
    {
        return $this->hasMany(ItemType::class);
    }

    /**
     * @return int
     */
    public function getSlotsCount()
    {
        return $this->getBehavior()->getGearSlotsCount();
    }

    public function getSlotTypeNames()
    {
        return $this->getBehavior()->getValidGearSlotTypes();
    }

    /**
     * @return ItemBaseBehavior
     */
    public function getBehavior(): ItemBaseBehavior
    {
        switch($this->name) {
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
            case self::POLEARM:
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
//            case self::EYE_WEAR:
//                return app(EyeWearBehavior::class);
            case self::HEAVY_ARMOR:
                return app(HeavyArmorBehavior::class);
            case self::LIGHT_ARMOR:
                return app(LightArmorBehavior::class);
            case self::LEGGINGS:
                return app(LeggingsBehavior::class);
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

        throw new UnknownBehaviorException($this->name, ItemBaseBehavior::class);
    }
}

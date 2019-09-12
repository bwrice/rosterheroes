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
 * @property Collection $slotTypes
 * @property AttackCollection $attacks
 */
class ItemBase extends Model
{

    use HasUniqueNames;

    public const DAGGER = 'dagger';
    public const SWORD = 'sword';
    public const AXE = 'axe';
    public const MACE = 'mace';
    public const BOW = 'bow';
    public const CROSSBOW = 'crossbow';
    public const THROWING_WEAPON = 'throwing-weapon';
    public const POLE_ARM = 'pole-arm';
    public const TWO_HAND_SWORD = 'two-hand-sword';
    public const TWO_HAND_AXE = 'two-hand-axe';
    public const WAND = 'wand';
    public const ORB = 'orb';
    public const STAFF = 'staff';
    public const PSIONIC_ONE_HAND = 'psionic-one-hand';
    public const PSIONIC_TWO_HAND = 'psionic-two-hand';
    public const SHIELD = 'shield';
    public const PSIONIC_SHIELD = 'psionic-shield';
    public const HELMET = 'helmet';
    public const CAP = 'cap';
//    public const EYE_WEAR = 'eye-wear';
    public const HEAVY_ARMOR = 'heavy-armor';
    public const LIGHT_ARMOR = 'light-armor';
    public const LEGGINGS = 'leggings';
    public const ROBES = 'robes';
    public const GLOVES = 'gloves';
    public const GAUNTLETS = 'gauntlets';
    public const SHOES = 'shoes';
    public const BOOTS = 'boots';
    public const BELT = 'belt';
    public const SASH = 'sash';
    public const NECKLACE = 'necklace';
    public const BRACELET = 'bracelet';
    public const RING = 'ring';
    public const CROWN = 'crown';

    protected $guarded = [];

    public function slotTypes()
    {
        return $this->belongsToMany( SlotType::class )->withTimestamps();
    }

    public function measurableTypes()
    {
        return $this->belongsToMany(MeasurableType::class, 'item_base_measurable_type', 'item_base_id', 'type_id')->withTimestamps();
    }

    public function attacks()
    {
        return $this->belongsToMany(Attack::class);
    }

    public function itemTypes()
    {
        return $this->hasMany(ItemType::class);
    }

//    public function itemGroup()
//    {
//        return $this->belongsTo(ItemGroup::class);
//    }

    /**
     * @return int
     */
    public function getSlotsCount()
    {
        return $this->getBehavior()->getSlotsCount();
    }

    /**
     * @return ItemBaseBehaviorInterface
     */
    public function getBehavior(): ItemBaseBehaviorInterface
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

        throw new UnknownBehaviorException($this->name, ItemBaseBehaviorInterface::class);
    }
}

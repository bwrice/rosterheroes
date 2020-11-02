<?php


namespace App\Services\Models\Reference;


use App\Domain\Behaviors\ItemBases\Armor\BeltBehavior;
use App\Domain\Behaviors\ItemBases\Armor\BootsBehavior;
use App\Domain\Behaviors\ItemBases\Armor\GauntletsBehavior;
use App\Domain\Behaviors\ItemBases\Armor\HeavyArmorBehavior;
use App\Domain\Behaviors\ItemBases\Armor\HelmetBehavior;
use App\Domain\Behaviors\ItemBases\Armor\LeggingsBehavior;
use App\Domain\Behaviors\ItemBases\Armor\LightArmorBehavior;
use App\Domain\Behaviors\ItemBases\Clothing\CapBehavior;
use App\Domain\Behaviors\ItemBases\Clothing\GlovesBehavior;
use App\Domain\Behaviors\ItemBases\Clothing\RobesBehavior;
use App\Domain\Behaviors\ItemBases\Clothing\SashBehavior;
use App\Domain\Behaviors\ItemBases\Clothing\ShoesBehavior;
use App\Domain\Behaviors\ItemBases\ItemBaseBehavior;
use App\Domain\Behaviors\ItemBases\Jewelry\BraceletBehavior;
use App\Domain\Behaviors\ItemBases\Jewelry\CrownBehavior;
use App\Domain\Behaviors\ItemBases\Jewelry\NecklaceBehavior;
use App\Domain\Behaviors\ItemBases\Jewelry\RingBehavior;
use App\Domain\Behaviors\ItemBases\Shields\PsionicShieldBehavior;
use App\Domain\Behaviors\ItemBases\Shields\ShieldBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\AxeBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\BowBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\CrossbowBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\DaggerBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\MaceBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\OrbBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\PoleArmBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\PsionicOneHandBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\PsionicTwoHandBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\StaffBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\SwordBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\ThrowingWeaponBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\TwoHandAxeBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\TwoHandSwordBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\WandBehavior;
use App\Domain\Models\ItemBase;
use Illuminate\Database\Eloquent\Collection;

class ItemBaseService extends ReferenceService
{
    protected string $behaviorClass = ItemBaseBehavior::class;

    public function __construct()
    {
        $this->behaviors[ItemBase::DAGGER] = app(DaggerBehavior::class);
        $this->behaviors[ItemBase::SWORD] = app(SwordBehavior::class);
        $this->behaviors[ItemBase::AXE] = app(AxeBehavior::class);
        $this->behaviors[ItemBase::MACE] = app(MaceBehavior::class);
        $this->behaviors[ItemBase::BOW] = app(BowBehavior::class);
        $this->behaviors[ItemBase::CROSSBOW] = app(CrossbowBehavior::class);
        $this->behaviors[ItemBase::THROWING_WEAPON] = app(ThrowingWeaponBehavior::class);
        $this->behaviors[ItemBase::POLEARM] = app(PoleArmBehavior::class);
        $this->behaviors[ItemBase::TWO_HAND_SWORD] = app(TwoHandSwordBehavior::class);
        $this->behaviors[ItemBase::TWO_HAND_AXE] = app(TwoHandAxeBehavior::class);
        $this->behaviors[ItemBase::WAND] = app(WandBehavior::class);
        $this->behaviors[ItemBase::ORB] = app(OrbBehavior::class);
        $this->behaviors[ItemBase::STAFF] = app(StaffBehavior::class);
        $this->behaviors[ItemBase::PSIONIC_ONE_HAND] = app(PsionicOneHandBehavior::class);
        $this->behaviors[ItemBase::PSIONIC_TWO_HAND] = app(PsionicTwoHandBehavior::class);
        $this->behaviors[ItemBase::SHIELD] = app(ShieldBehavior::class);
        $this->behaviors[ItemBase::PSIONIC_SHIELD] = app(PsionicShieldBehavior::class);
        $this->behaviors[ItemBase::HELMET] = app(HelmetBehavior::class);
        $this->behaviors[ItemBase::CAP] = app(CapBehavior::class);
        $this->behaviors[ItemBase::HEAVY_ARMOR] = app(HeavyArmorBehavior::class);
        $this->behaviors[ItemBase::LIGHT_ARMOR] = app(LightArmorBehavior::class);
        $this->behaviors[ItemBase::LEGGINGS] = app(LeggingsBehavior::class);
        $this->behaviors[ItemBase::ROBES] = app(RobesBehavior::class);
        $this->behaviors[ItemBase::GLOVES] = app(GlovesBehavior::class);
        $this->behaviors[ItemBase::GAUNTLETS] = app(GauntletsBehavior::class);
        $this->behaviors[ItemBase::BOOTS] = app(BootsBehavior::class);
        $this->behaviors[ItemBase::SHOES] = app(ShoesBehavior::class);
        $this->behaviors[ItemBase::BELT] = app(BeltBehavior::class);
        $this->behaviors[ItemBase::SASH] = app(SashBehavior::class);
        $this->behaviors[ItemBase::NECKLACE] = app(NecklaceBehavior::class);
        $this->behaviors[ItemBase::BRACELET] = app(BraceletBehavior::class);
        $this->behaviors[ItemBase::RING] = app(RingBehavior::class);
        $this->behaviors[ItemBase::CROWN] = app(CrownBehavior::class);
    }

    protected function all(): Collection
    {
        return ItemBase::all();
    }
}

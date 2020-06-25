<?php

namespace App\Domain\Models;

use App\Domain\Models\ChestBlueprint;
use App\Domain\Actions\GenerateItemFromBlueprintAction;
use App\Domain\Collections\AttackCollection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class ItemBlueprint
 * @package App
 *
 * @property int $id
 * @property string|null $item_name
 * @property int|null $enchantment_power
 * @property string|null $description
 * @property string $reference_id
 *
 * @property ItemClass|null $itemClass
 * @property ItemType|null $itemType
 *
 * @property Collection $enchantments
 * @property Collection $itemTypes
 * @property Collection $itemBases
 * @property Collection $itemClasses
 * @property Collection $materials
 * @property AttackCollection $attacks
 */
class ItemBlueprint extends Model
{
    /*
     * Starter Item Names
     */
    public const STARTER_SWORD = 'Starter Sword';
    public const STARTER_SHIELD = 'Starter Shield';
    public const STARTER_BOW = 'Starter Bow';
    public const STARTER_STAFF = 'Starter Staff';
    public const STARTER_LIGHT_ARMOR = 'Starter Cuirass';
    public const STARTER_HEAVY_ARMOR = 'Starter Breastplate';
    public const STARTER_ROBES = 'Starter Frock';
    public const STARTER_BELT = 'Starter Belt';
    public const STARTER_HELMET = 'Starter Helmet';
    public const STARTER_CAP = 'Starter Cap';
    public const STARTER_BOOTS = 'Starter Boots';
    public const STARTER_GLOVES = 'Starter Gloves';

    /*
     * Random Items Reference IDs
     */
    public const RANDOM_ENCHANTED_ITEM = 'H';
    public const RANDOM_ENCHANTED_DAGGER = 'I';
    public const RANDOM_ENCHANTED_SWORD = 'J';
    public const RANDOM_ENCHANTED_AXE = 'K';
    public const RANDOM_ENCHANTED_MACE = 'L';
    public const RANDOM_ENCHANTED_BOW = 'M';
    public const RANDOM_ENCHANTED_CROSSBOW = 'N';
    public const RANDOM_ENCHANTED_THROWING_WEAPON = 'O';
    public const RANDOM_ENCHANTED_POLEARM = 'P';
    public const RANDOM_ENCHANTED_TWO_HAND_SWORD = 'Q';
    public const RANDOM_ENCHANTED_TWO_HAND_AXE = 'R';
    public const RANDOM_ENCHANTED_WAND = 'S';
    public const RANDOM_ENCHANTED_ORB = 'T';
    public const RANDOM_ENCHANTED_STAFF = 'U';
    public const RANDOM_ENCHANTED_PSIONIC_ONE_HAND = 'V';
    public const RANDOM_ENCHANTED_PSIONIC_TWO_HAND = 'W';
    public const RANDOM_ENCHANTED_SHIELD = 'X';
    public const RANDOM_ENCHANTED_PSIONIC_SHIELD = 'Y';
    public const RANDOM_ENCHANTED_HELMET = 'Z';
    public const RANDOM_ENCHANTED_CAP = 'AA';
    public const RANDOM_ENCHANTED_HEAVY_ARMOR = 'AB';
    public const RANDOM_ENCHANTED_LIGHT_ARMOR = 'AC';
    public const RANDOM_ENCHANTED_LEGGINGS = 'AD';
    public const RANDOM_ENCHANTED_ROBES = 'AE';
    public const RANDOM_ENCHANTED_GLOVES = 'AF';
    public const RANDOM_ENCHANTED_GAUNTLETS = 'AG';
    public const RANDOM_ENCHANTED_SHOES = 'AH';
    public const RANDOM_ENCHANTED_BOOTS = 'AI';
    public const RANDOM_ENCHANTED_BELT = 'AJ';
    public const RANDOM_ENCHANTED_SASH = 'AK';
    public const RANDOM_ENCHANTED_NECKLACE = 'AL';
    public const RANDOM_ENCHANTED_BRACELET = 'AM';
    public const RANDOM_ENCHANTED_RING = 'AN';
    public const RANDOM_ENCHANTED_CROWN = 'AO';
    public const RANDOM_ENCHANTED_LOW_TIER_ITEM = 'AP';
    public const RANDOM_ENCHANTED_LOW_TIER_DAGGER = 'AQ';
    public const RANDOM_ENCHANTED_LOW_TIER_SWORD = 'AR';
    public const RANDOM_ENCHANTED_LOW_TIER_AXE = 'AS';
    public const RANDOM_ENCHANTED_LOW_TIER_MACE = 'AT';
    public const RANDOM_ENCHANTED_LOW_TIER_BOW = 'AU';
    public const RANDOM_ENCHANTED_LOW_TIER_CROSSBOW = 'AV';
    public const RANDOM_ENCHANTED_LOW_TIER_THROWING_WEAPON = 'AW';
    public const RANDOM_ENCHANTED_LOW_TIER_POLEARM = 'AX';
    public const RANDOM_ENCHANTED_LOW_TIER_TWO_HAND_SWORD = 'AY';
    public const RANDOM_ENCHANTED_LOW_TIER_TWO_HAND_AXE = 'AZ';
    public const RANDOM_ENCHANTED_LOW_TIER_WAND = 'BA';
    public const RANDOM_ENCHANTED_LOW_TIER_ORB = 'BB';
    public const RANDOM_ENCHANTED_LOW_TIER_STAFF = 'BC';
    public const RANDOM_ENCHANTED_LOW_TIER_PSIONIC_ONE_HAND = 'BD';
    public const RANDOM_ENCHANTED_LOW_TIER_PSIONIC_TWO_HAND = 'BE';
    public const RANDOM_ENCHANTED_LOW_TIER_SHIELD = 'BF';
    public const RANDOM_ENCHANTED_LOW_TIER_PSIONIC_SHIELD = 'BG';
    public const RANDOM_ENCHANTED_LOW_TIER_HELMET = 'BH';
    public const RANDOM_ENCHANTED_LOW_TIER_CAP = 'BI';
    public const RANDOM_ENCHANTED_LOW_TIER_HEAVY_ARMOR = 'BJ';
    public const RANDOM_ENCHANTED_LOW_TIER_LIGHT_ARMOR = 'BK';
    public const RANDOM_ENCHANTED_LOW_TIER_LEGGINGS = 'BL';
    public const RANDOM_ENCHANTED_LOW_TIER_ROBES = 'BM';
    public const RANDOM_ENCHANTED_LOW_TIER_GLOVES = 'BN';
    public const RANDOM_ENCHANTED_LOW_TIER_GAUNTLETS = 'BO';
    public const RANDOM_ENCHANTED_LOW_TIER_SHOES = 'BP';
    public const RANDOM_ENCHANTED_LOW_TIER_BOOTS = 'BQ';
    public const RANDOM_ENCHANTED_LOW_TIER_BELT = 'BR';
    public const RANDOM_ENCHANTED_LOW_TIER_SASH = 'BS';
    public const RANDOM_ENCHANTED_LOW_TIER_NECKLACE = 'BT';
    public const RANDOM_ENCHANTED_LOW_TIER_BRACELET = 'BU';
    public const RANDOM_ENCHANTED_LOW_TIER_RING = 'BV';
    public const RANDOM_ENCHANTED_LOW_TIER_CROWN = 'BW';
    public const RANDOM_ENCHANTED_MID_TIER_ITEM = 'BX';
    public const RANDOM_ENCHANTED_MID_TIER_DAGGER = 'BY';
    public const RANDOM_ENCHANTED_MID_TIER_SWORD = 'BZ';
    public const RANDOM_ENCHANTED_MID_TIER_AXE = 'CA';
    public const RANDOM_ENCHANTED_MID_TIER_MACE = 'CB';
    public const RANDOM_ENCHANTED_MID_TIER_BOW = 'CC';
    public const RANDOM_ENCHANTED_MID_TIER_CROSSBOW = 'CD';
    public const RANDOM_ENCHANTED_MID_TIER_THROWING_WEAPON = 'CE';
    public const RANDOM_ENCHANTED_MID_TIER_POLEARM = 'CF';
    public const RANDOM_ENCHANTED_MID_TIER_TWO_HAND_SWORD = 'CG';
    public const RANDOM_ENCHANTED_MID_TIER_TWO_HAND_AXE = 'CH';
    public const RANDOM_ENCHANTED_MID_TIER_WAND = 'CI';
    public const RANDOM_ENCHANTED_MID_TIER_ORB = 'CJ';
    public const RANDOM_ENCHANTED_MID_TIER_STAFF = 'CK';
    public const RANDOM_ENCHANTED_MID_TIER_PSIONIC_ONE_HAND = 'CL';
    public const RANDOM_ENCHANTED_MID_TIER_PSIONIC_TWO_HAND = 'CM';
    public const RANDOM_ENCHANTED_MID_TIER_SHIELD = 'CN';
    public const RANDOM_ENCHANTED_MID_TIER_PSIONIC_SHIELD = 'CO';
    public const RANDOM_ENCHANTED_MID_TIER_HELMET = 'CP';
    public const RANDOM_ENCHANTED_MID_TIER_CAP = 'CQ';
    public const RANDOM_ENCHANTED_MID_TIER_HEAVY_ARMOR = 'CR';
    public const RANDOM_ENCHANTED_MID_TIER_LIGHT_ARMOR = 'CS';
    public const RANDOM_ENCHANTED_MID_TIER_LEGGINGS = 'CT';
    public const RANDOM_ENCHANTED_MID_TIER_ROBES = 'CU';
    public const RANDOM_ENCHANTED_MID_TIER_GLOVES = 'CV';
    public const RANDOM_ENCHANTED_MID_TIER_GAUNTLETS = 'CW';
    public const RANDOM_ENCHANTED_MID_TIER_SHOES = 'CX';
    public const RANDOM_ENCHANTED_MID_TIER_BOOTS = 'CY';
    public const RANDOM_ENCHANTED_MID_TIER_BELT = 'CZ';
    public const RANDOM_ENCHANTED_MID_TIER_SASH = 'DA';
    public const RANDOM_ENCHANTED_MID_TIER_NECKLACE = 'DB';
    public const RANDOM_ENCHANTED_MID_TIER_BRACELET = 'DC';
    public const RANDOM_ENCHANTED_MID_TIER_RING = 'DD';
    public const RANDOM_ENCHANTED_MID_TIER_CROWN = 'DE';
    public const RANDOM_ENCHANTED_HIGH_TIER_ITEM = 'DF';
    public const RANDOM_ENCHANTED_HIGH_TIER_SWORD = 'DG';
    public const RANDOM_ENCHANTED_HIGH_TIER_DAGGER = 'DH';
    public const RANDOM_ENCHANTED_HIGH_TIER_AXE = 'DI';
    public const RANDOM_ENCHANTED_HIGH_TIER_MACE = 'DJ';
    public const RANDOM_ENCHANTED_HIGH_TIER_BOW = 'DK';
    public const RANDOM_ENCHANTED_HIGH_TIER_CROSSBOW = 'DL';
    public const RANDOM_ENCHANTED_HIGH_TIER_THROWING_WEAPON = 'DM';
    public const RANDOM_ENCHANTED_HIGH_TIER_POLEARM = 'DN';
    public const RANDOM_ENCHANTED_HIGH_TIER_TWO_HAND_SWORD = 'DO';
    public const RANDOM_ENCHANTED_HIGH_TIER_TWO_HAND_AXE = 'DP';
    public const RANDOM_ENCHANTED_HIGH_TIER_WAND = 'DQ';
    public const RANDOM_ENCHANTED_HIGH_TIER_ORB = 'DR';
    public const RANDOM_ENCHANTED_HIGH_TIER_STAFF = 'DS';
    public const RANDOM_ENCHANTED_HIGH_TIER_PSIONIC_ONE_HAND = 'DT';
    public const RANDOM_ENCHANTED_HIGH_TIER_PSIONIC_TWO_HAND = 'DU';
    public const RANDOM_ENCHANTED_HIGH_TIER_SHIELD = 'DV';
    public const RANDOM_ENCHANTED_HIGH_TIER_PSIONIC_SHIELD = 'DW';
    public const RANDOM_ENCHANTED_HIGH_TIER_HELMET = 'DX';
    public const RANDOM_ENCHANTED_HIGH_TIER_CAP = 'DY';
    public const RANDOM_ENCHANTED_HIGH_TIER_HEAVY_ARMOR = 'DZ';
    public const RANDOM_ENCHANTED_HIGH_TIER_LIGHT_ARMOR = 'EA';
    public const RANDOM_ENCHANTED_HIGH_TIER_LEGGINGS = 'EB';
    public const RANDOM_ENCHANTED_HIGH_TIER_ROBES = 'EC';
    public const RANDOM_ENCHANTED_HIGH_TIER_GLOVES = 'ED';
    public const RANDOM_ENCHANTED_HIGH_TIER_GAUNTLETS = 'EF';
    public const RANDOM_ENCHANTED_HIGH_TIER_SHOES = 'EG';
    public const RANDOM_ENCHANTED_HIGH_TIER_BOOTS = 'EH';
    public const RANDOM_ENCHANTED_HIGH_TIER_BELT = 'EI';
    public const RANDOM_ENCHANTED_HIGH_TIER_SASH = 'EJ';
    public const RANDOM_ENCHANTED_HIGH_TIER_NECKLACE = 'EK';
    public const RANDOM_ENCHANTED_HIGH_TIER_BRACELET = 'EL';
    public const RANDOM_ENCHANTED_HIGH_TIER_RING = 'EM';
    public const RANDOM_ENCHANTED_HIGH_TIER_CROWN = 'EN';

    public const STARTER_BELT_REFERENCE = 'EO';
    public const STARTER_HELMET_REFERENCE = 'EP';
    public const STARTER_CAP_REFERENCE = 'EQ';
    public const STARTER_BOOTS_REFERENCE = 'ER';
    public const STARTER_GLOVES_REFERENCE = 'ES';

    public const GENERIC_LOW_TIER_DAGGER = 'ET';
    public const GENERIC_LOW_TIER_SWORD = 'EU';
    public const GENERIC_LOW_TIER_AXE = 'EV';
    public const GENERIC_LOW_TIER_BOW = 'EW';
    public const GENERIC_LOW_TIER_POLEARM = 'EX';
    public const GENERIC_LOW_TIER_WAND = 'EY';
    public const GENERIC_LOW_TIER_STAFF = 'EZ';
    public const GENERIC_LOW_TIER_SHIELD = 'FA';
    public const GENERIC_LOW_TIER_HELMET = 'FB';
    public const GENERIC_LOW_TIER_HEAVY_ARMOR = 'FC';
    public const GENERIC_LOW_TIER_LIGHT_ARMOR = 'FD';
    public const GENERIC_LOW_TIER_ROBES = 'FE';
    public const GENERIC_LOW_TIER_GLOVES = 'FF';
    public const GENERIC_LOW_TIER_GAUNTLETS = 'FG';
    public const GENERIC_LOW_TIER_SHOES= 'FH';
    public const GENERIC_LOW_TIER_BOOTS = 'FI';
    public const GENERIC_LOW_TIER_BELT = 'FJ';
    public const GENERIC_LOW_TIER_SASH = 'FK';
    public const GENERIC_LOW_TIER_LEGGINGS = 'FL';


    protected $guarded = [];

    /**
     * @return BelongsToMany
     */
    public function enchantments()
    {
        return $this->belongsToMany(Enchantment::class, 'enchantment_item_blueprint', 'blueprint_id', 'ench_id')->withTimestamps();
    }

    /**
     * @return BelongsToMany
     */
    public function attacks()
    {
        return $this->belongsToMany(Attack::class)->withTimestamps();
    }

    /**
     * @return BelongsToMany
     */
    public function itemTypes()
    {
        return $this->belongsToMany(ItemType::class)->withTimestamps();
    }

    /**
     * @return BelongsToMany
     */
    public function itemBases()
    {
        return $this->belongsToMany(ItemBase::class)->withTimestamps();
    }

    /**
     * @return BelongsToMany
     */
    public function itemClasses()
    {
        return $this->belongsToMany(ItemClass::class)->withTimestamps();
    }

    /**
     * @return BelongsToMany
     */
    public function materials()
    {
        return $this->belongsToMany(Material::class)->withTimestamps();
    }

    public function chestBlueprints()
    {
        return $this->belongsToMany(ChestBlueprint::class)->withPivot(['chance', 'count'])->withTimestamps();
    }

    /**
     * @return Item
     */
    public function generate(): Item
    {
        /** @var GenerateItemFromBlueprintAction $domainAction */
        $domainAction = app(GenerateItemFromBlueprintAction::class);
        return $domainAction->execute($this);
    }
}

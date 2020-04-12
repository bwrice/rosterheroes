<?php

namespace App\Domain\Models;

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

    /*
     * Random Items Reference IDs
     */
    public const RANDOM_ENCHANTED_REFERENCE = 'H';
    public const RANDOM_ENCHANTED_DAGGER = 'I';
    public const RANDOM_ENCHANTED_SWORD = 'J';
    public const RANDOM_ENCHANTED_AXE = 'K';
    public const RANDOM_ENCHANTED_MACE = 'L';
    public const RANDOM_ENCHANTED_BOW = 'M';
    public const RANDOM_ENCHANTED_CROSSBOW = 'N';

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

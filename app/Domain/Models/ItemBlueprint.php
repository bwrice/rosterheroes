<?php

namespace App\Domain\Models;

use App\Domain\Actions\GenerateItemFromBlueprintAction;
use App\Domain\Models\Enchantment;
use App\Domain\Models\Item;
use App\Events\ItemBlueprintCreated;
use App\Domain\Models\ItemClass;
use App\Domain\Models\ItemGroup;
use App\Domain\Models\ItemBase;
use App\Domain\Models\ItemType;
use App\Domain\Models\MaterialType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class ItemBlueprint
 * @package App
 *
 * @property int $id
 * @property string|null $item_name
 * @property int|null $attack_power
 * @property int|null $enchantment_power
 * @property int|null $item_class_id
 * @property int|null $item_type_id
 * @property int|null $item_base_id
 * @property int|null $item_group_id
 * @property string|null $description
 *
 * @property ItemClass|null $itemClass
 * @property ItemType|null $itemType
 *
 * @property Collection $enchantments
 * @property Collection $itemTypes
 * @property Collection $itemBases
 * @property Collection $itemClasses
 * @property Collection $materialTypes
 */
class ItemBlueprint extends Model
{
    public const STARTER_SWORD = 'Starter Sword';
    public const STARTER_SHIELD = 'Starter Shield';
    public const STARTER_BOW = 'Starter Bow';
    public const STARTER_STAFF = 'Starter Staff';

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
    public function itemTypes()
    {
        return $this->belongsToMany(ItemType::class, 'item_blueprint_item_type', 'blueprint_id', 'i_type_id')->withTimestamps();
    }

    /**
     * @return BelongsToMany
     */
    public function itemBases()
    {
        return $this->belongsToMany(ItemBase::class, 'item_base_item_blueprint', 'blueprint_id', 'i_base_id')->withTimestamps();
    }

    /**
     * @return BelongsToMany
     */
    public function itemClasses()
    {
        return $this->belongsToMany(ItemClass::class, 'item_blueprint_item_class', 'blueprint_id', 'i_class_id')->withTimestamps();
    }

    /**
     * @return BelongsToMany
     */
    public function materialTypes()
    {
        return $this->belongsToMany(MaterialType::class, 'item_blueprint_material_type', 'blueprint_id', 'm_type_id')->withTimestamps();
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

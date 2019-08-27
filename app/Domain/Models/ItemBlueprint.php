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
 * @property Collection $materialTypes
 */
class ItemBlueprint extends Model
{
    public const STARTER_SWORD = 'Starter Sword';
    public const STARTER_SHIELD = 'Starter Shield';
    public const STARTER_BOW = 'Starter Bow';
    public const STARTER_STAFF = 'Starter Staff';

    protected $guarded = [];

    public function itemClass()
    {
        return $this->belongsTo(ItemClass::class);
    }

    public function itemType()
    {
        return $this->belongsTo(ItemType::class);
    }

    public function enchantments()
    {
        return $this->belongsToMany(Enchantment::class, 'enchantment_item_blueprint', 'blueprint_id', 'ench_id')->withTimestamps();
    }

    public function itemTypes()
    {
        return $this->belongsToMany(ItemType::class, 'item_blueprint_item_type', 'blueprint_id', 'i_type_id')->withTimestamps();
    }

    public function itemBases()
    {
        return $this->belongsToMany(ItemBase::class, 'item_base_item_blueprint', 'blueprint_id', 'base_id')->withTimestamps();
    }

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

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
 * @property string $item_name
 * @property int $attack_power
 * @property int $enchantment_power
 *
 * @property Collection $enchantments
 * @property ItemClass $itemClass
 * @property ItemType $itemType
 * @property ItemBase $itemBase
 * @property ItemGroup $itemGroup
 * @property MaterialType $materialType
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

    public function materialType()
    {
        return $this->belongsTo(MaterialType::class);
    }

    public function itemBase()
    {
        return $this->belongsTo(ItemBase::class);
    }

    public function itemGroup()
    {
        return $this->belongsTo(ItemGroup::class);
    }

    public function enchantments()
    {
        return $this->belongsToMany(Enchantment::class, 'enchantment_item_blueprint', 'blueprint_id', 'ench_id')->withTimestamps();
    }

    /**
     * @return Item
     */
    public function generate(): Item
    {
        $action = new GenerateItemFromBlueprintAction($this);
        return $action();
    }
}

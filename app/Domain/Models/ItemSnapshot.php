<?php

namespace App\Domain\Models;

use App\Domain\Interfaces\HasAttackSnapshots;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Class ItemSnapshot
 * @package App
 *
 * @property int $id
 * @property string $uuid
 * @property int $item_id
 * @property int $hero_snapshot_id
 * @property int $item_type_id
 * @property int $material_id
 * @property string|null $name
 *
 * @property Item $item
 * @property HeroSnapshot $heroSnapshot
 * @property ItemType $itemType
 * @property Material $material
 *
 */
class ItemSnapshot extends Model implements HasAttackSnapshots
{
    protected $guarded = [];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function heroSnapshot()
    {
        return $this->belongsTo(HeroSnapshot::class);
    }

    public function itemType()
    {
        return $this->belongsTo(ItemType::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
    public function attackSnapshots(): MorphMany
    {
        return $this->morphMany(AttackSnapshot::class, 'attacker');
    }
}

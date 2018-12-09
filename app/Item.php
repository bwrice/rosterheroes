<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Item
 * @package App
 *
 * @property int $id
 *
 * @property ItemType $itemType
 */
class Item extends Model
{
    protected $guarded = [];

    public function enchantments()
    {
        return $this->belongsToMany(Enchantment::class)->withTimestamps();
    }

    public function itemType()
    {
        return $this->belongsTo(ItemType::class);
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ItemType
 * @package App
 *
 * @property int $id
 * @property string $name
 *
 * @property ItemBase $itemBase
 */
class ItemType extends Model
{
    protected $guarded = [];

    public function materialTypes()
    {
        return $this->belongsToMany(MaterialType::class, 'item_type_material_type', 'i_type_id', 'm_type_id')->withTimestamps();
    }

    public function itemBase()
    {
        return $this->belongsTo(ItemBase::class);
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemType extends Model
{
    protected $guarded = [];

    public function materialTypes()
    {
        return $this->belongsToMany(MaterialType::class, 'item_type_material_type', 'i_type_id', 'm_type_id')->withTimestamps();
    }
}

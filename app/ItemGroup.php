<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemGroup extends Model
{
    const WEAPON = 'weapon';
    const ARMOR = 'armor';
    const SHIELD = 'shield';
    const JEWELRY = 'jewelry';

    protected $guarded = [];


    public function itemBases()
    {
        return $this->hasMany(ItemBase::class);
    }
}

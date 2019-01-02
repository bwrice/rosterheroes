<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class StoreHouseType
 * @package App
 *
 * @property int $id
 * @property string $name
 */
class StoreHouseType extends Model
{
    const DEPOT = 'depot';

    protected $guarded = [];
}

<?php

namespace App\Domain\Models;

use App\Domain\Behaviors\StoreHouses\DepotBehavior;
use App\Domain\Behaviors\StoreHouses\StoreHouseBehavior;
use App\Exceptions\UnknownBehaviorException;
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

    public function getBehavior(): StoreHouseBehavior
    {
        switch ($this->name) {
            case self::DEPOT:
                return app(DepotBehavior::class);
        }
        throw new UnknownBehaviorException($this->name, StoreHouseBehavior::class);
    }
}

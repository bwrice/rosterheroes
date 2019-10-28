<?php

namespace App\Domain\Models;

use App\Domain\Behaviors\StoreHouses\ShackBehavior;
use App\Domain\Behaviors\StoreHouses\HouseTypeBehavior;
use App\Exceptions\UnknownBehaviorException;
use Illuminate\Database\Eloquent\Model;

/**
 * Class StoreHouseType
 * @package App
 *
 * @property int $id
 * @property string $name
 */
class HouseType extends Model
{
    const SHACK = 'shack';

    protected $guarded = [];

    public function getBehavior(): HouseTypeBehavior
    {
        switch ($this->name) {
            case self::SHACK:
                return app(ShackBehavior::class);
        }
        throw new UnknownBehaviorException($this->name, HouseTypeBehavior::class);
    }
}

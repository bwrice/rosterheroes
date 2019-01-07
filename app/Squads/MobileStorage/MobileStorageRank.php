<?php

namespace App\Squads\MobileStorage;

use App\Squads\MobileStorage\Behaviors\MobileStorageRankBehavior;
use App\Squads\MobileStorage\Behaviors\WagonBehavior;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MobileStorageRank
 * @package App\Squads\MobileStorage
 *
 * @property int $id
 * @property string $name
 */
class MobileStorageRank extends Model
{
    const WAGON = 'wagon';

    protected $guarded = [];

    /**
     * @return MobileStorageRankBehavior
     */
    public function getBehavior()
    {
        switch($this->name) {
            case self::WAGON:
                return app(WagonBehavior::class);
        }

        throw new \RuntimeException("Unknown mobile storage rank name when retrieving behavior");
    }

    /**
     * @return MobileStorageRank
     */
    public static function getStarting()
    {
        return self::where('name', '=', self::WAGON)->first();
    }
}

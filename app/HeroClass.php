<?php

namespace App;

use App\Heroes\Classes\HeroClassBehavior;
use App\Heroes\Classes\RangerBehavior;
use App\Heroes\Classes\SorcererBehavior;
use App\Heroes\Classes\WarriorBehavior;
use Illuminate\Database\Eloquent\Model;

/**
 * Class HeroClass
 * @package App
 *
 * @property int $id
 * @property string $name
 */
class HeroClass extends Model
{
    const WARRIOR = 'warrior';
    const SORCERER = 'sorcerer';
    const RANGER = 'ranger';

    protected $guarded = [];

    /**
     * @return HeroClassBehavior
     */
    public function getBehavior()
    {
        switch( $this->name ) {
            case self::WARRIOR:
                return app()->make(WarriorBehavior::class);
            case self::RANGER:
                return app()->make(RangerBehavior::class);
            case self::SORCERER:
                return app()->make(SorcererBehavior::class);
        }

        throw new \RuntimeException("Unable to determine Hero Class Behavior");
    }
}

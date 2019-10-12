<?php

namespace App\Domain\Models;

use App\Domain\Behaviors\Sports\BaseballBehavior;
use App\Domain\Behaviors\Sports\BasketballBehavior;
use App\Domain\Behaviors\Sports\FootballBehavior;
use App\Domain\Behaviors\Sports\HockeyBehavior;
use App\Domain\Behaviors\Sports\SportBehavior;
use App\Domain\Collections\PositionCollection;
use App\Domain\Models\League;
use App\Domain\Models\Position;
use App\Exceptions\UnknownBehaviorException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Sport
 * @package App
 *
 * @property int $id
 * @property string $name
 *
 * @property PositionCollection $positions
 * @property Collection $statTypes
 * @property Collection $leagues
 */
class Sport extends Model
{
    protected $guarded = [];

    const FOOTBALL = 'football';
    const BASKETBALL = 'basketball';
    const HOCKEY = 'hockey';
    const BASEBALL = 'baseball';

    public function leagues()
    {
        return $this->hasMany(League::class);
    }

    public function positions()
    {
        return $this->hasMany(Position::class);
    }

    public function statTypes()
    {
        return $this->hasMany(StatType::class);
    }

    public function getBehavior(): SportBehavior
    {
        switch ($this->name) {
            case self::FOOTBALL:
                return app(FootballBehavior::class);
            case self::BASEBALL:
                return app(BaseballBehavior::class);
            case self::BASKETBALL:
                return app(BasketballBehavior::class);
            case self::HOCKEY:
                return app(HockeyBehavior::class);
        }
        throw new UnknownBehaviorException($this->name, SportBehavior::class);
    }
}

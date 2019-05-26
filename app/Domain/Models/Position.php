<?php

namespace App\Domain\Models;

use App\Domain\Behaviors\Positions\PositionBehavior;
use App\Domain\Models\Player;
use App\Domain\Models\HeroRace;
use App\Domain\Models\League;
use App\Domain\Collections\PositionCollection;
use App\Domain\Models\Sport;
use App\Domain\Models\Traits\HasUniqueNames;
use App\Exceptions\UnknownBehaviorException;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Position
 * @package App
 *
 * @property string $name
 * @property int $sport_id
 *
 * @property Sport $sport
 */
class Position extends Model
{
    use HasUniqueNames;

    // Football
    public const QUARTERBACK = 'quarterback';
    public const RUNNING_BACK = 'running-back';
    public const WIDE_RECEIVER = 'wide-receiver';
    public const TIGHT_END = 'tight-end';

    // Baseball
    public const CATCHER = 'catcher';
    public const FIRST_BASE = 'first-base';
    public const SECOND_BASE = 'second-base';
    public const THIRD_BASE = 'third-base';
    public const SHORTSTOP = 'shortstop';
    public const PITCHER = 'pitcher';
    public const OUTFIELD = 'outfield';

    // Basketball
    public const POINT_GUARD = 'point-guard';
    public const SHOOTING_GUARD = 'shooting-guard';
    public const SMALL_FORWARD = 'small-forward';
    public const POWER_FORWARD = 'power-forward';
    public const BASKETBALL_CENTER = 'basketball-center';

    // Hockey
    public const LEFT_WING = 'left-wing';
    public const RIGHT_WING = 'right-wing';
    public const DEFENSEMAN = 'defenseman';
    public const GOALIE = 'goalie';
    public const HOCKEY_CENTER = 'hockey-center';

    protected $guarded = [];

    public function newCollection(array $models = [])
    {
        return new PositionCollection($models);
    }

    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }

    public function players()
    {
        return $this->belongsToMany(Player::class)->withTimestamps();
    }

    public function heroRaces()
    {
        return $this->belongsToMany(HeroRace::class)->withTimestamps();
    }

    public function getBehavior(): PositionBehavior
    {
        switch ($this->name) {
            case self::QUARTERBACK;
                return new PositionBehavior(75, 16, 'QB');
            case self::RUNNING_BACK;
                return new PositionBehavior(50, 14, 'RB');
            case self::WIDE_RECEIVER;
                return new PositionBehavior(50, 14, 'WR');
            case self::TIGHT_END;
                return new PositionBehavior(35, 16, 'TE');

            case self::PITCHER;
                return new PositionBehavior(70, 30, 'P');
            case self::CATCHER;
                return new PositionBehavior(40, 150, 'C');
            case self::SHORTSTOP;
                return new PositionBehavior(50, 150, 'SS');
            case self::FIRST_BASE;
                return new PositionBehavior(50, 150, '1B');
            case self::SECOND_BASE;
                return new PositionBehavior(50, 150, '2B');
            case self::THIRD_BASE;
                return new PositionBehavior(50, 150, '3B');
            case self::OUTFIELD;
                return new PositionBehavior(60, 150, 'OF');

            case self::SMALL_FORWARD;
                return new PositionBehavior(90, 80, 'SF');
            case self::SHOOTING_GUARD;
                return new PositionBehavior(90, 80, 'SG');
            case self::POINT_GUARD;
                return new PositionBehavior(90, 80, 'PG');
            case self::POWER_FORWARD;
                return new PositionBehavior(80, 80, 'PF');
            case self::BASKETBALL_CENTER;
                return new PositionBehavior(80, 80, 'C');

            case self::GOALIE;
                return new PositionBehavior(50, 80, 'G');
            case self::RIGHT_WING;
                return new PositionBehavior(40, 80, 'RW');
            case self::LEFT_WING;
                return new PositionBehavior(40, 80, 'LW');
            case self::DEFENSEMAN;
                return new PositionBehavior(30, 80, 'D');
            case self::HOCKEY_CENTER;
                return new PositionBehavior(40, 80, 'C');
        }

        throw new UnknownBehaviorException($this->name, PositionBehavior::class);
    }
}

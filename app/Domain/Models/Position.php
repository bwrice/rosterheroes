<?php

namespace App\Domain\Models;

use App\Domain\Behaviors\Positions\BasketballCenterBehavior;
use App\Domain\Behaviors\Positions\CatcherBehavior;
use App\Domain\Behaviors\Positions\DefensemanBehavior;
use App\Domain\Behaviors\Positions\FirstBaseBehavior;
use App\Domain\Behaviors\Positions\GoalieBehavior;
use App\Domain\Behaviors\Positions\HockeyCenterBehavior;
use App\Domain\Behaviors\Positions\LeftWingBehavior;
use App\Domain\Behaviors\Positions\OutfieldBehavior;
use App\Domain\Behaviors\Positions\PitcherBehavior;
use App\Domain\Behaviors\Positions\PointGuardBehavior;
use App\Domain\Behaviors\Positions\PositionBehavior;
use App\Domain\Behaviors\Positions\PowerForwardBehavior;
use App\Domain\Behaviors\Positions\QuarterbackBehavior;
use App\Domain\Behaviors\Positions\RightWingBehavior;
use App\Domain\Behaviors\Positions\RunningBackBehavior;
use App\Domain\Behaviors\Positions\SecondBaseBehavior;
use App\Domain\Behaviors\Positions\ShootingGuardBehavior;
use App\Domain\Behaviors\Positions\ShortstopBehavior;
use App\Domain\Behaviors\Positions\SmallForwardBehavior;
use App\Domain\Behaviors\Positions\ThirdBaseBehavior;
use App\Domain\Behaviors\Positions\TightEndBehavior;
use App\Domain\Behaviors\Positions\WideReceiverBehavior;
use App\Domain\Models\Player;
use App\Domain\Models\HeroRace;
use App\Domain\Models\League;
use App\Domain\Collections\PositionCollection;
use App\Domain\Models\Sport;
use App\Domain\Models\Traits\HasUniqueNames;
use App\Domain\QueryBuilders\PositionQueryBuilder;
use App\Exceptions\UnknownBehaviorException;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Position
 * @package App
 *
 * @property int $id
 * @property string $name
 * @property int $sport_id
 *
 * @property Sport $sport
 *
 * @method static PositionQueryBuilder query()
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

    public function newEloquentBuilder($query)
    {
        return new PositionQueryBuilder($query);
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
                return app(QuarterbackBehavior::class);
            case self::RUNNING_BACK;
                return app(RunningBackBehavior::class);
            case self::WIDE_RECEIVER;
                return app(WideReceiverBehavior::class);
            case self::TIGHT_END;
                return app(TightEndBehavior::class);

            case self::PITCHER;
                return app(PitcherBehavior::class);
            case self::CATCHER;
                return app(CatcherBehavior::class);
            case self::SHORTSTOP;
                return app(ShortstopBehavior::class);
            case self::FIRST_BASE;
                return app(FirstBaseBehavior::class);
            case self::SECOND_BASE;
                return app(SecondBaseBehavior::class);
            case self::THIRD_BASE;
                return app(ThirdBaseBehavior::class);
            case self::OUTFIELD;
                return app(OutfieldBehavior::class);

            case self::SMALL_FORWARD;
                return app(SmallForwardBehavior::class);
            case self::SHOOTING_GUARD;
                return app(ShootingGuardBehavior::class);
            case self::POINT_GUARD;
                return app(PointGuardBehavior::class);
            case self::POWER_FORWARD;
                return app(PowerForwardBehavior::class);
            case self::BASKETBALL_CENTER;
                return app(BasketballCenterBehavior::class);

            case self::GOALIE;
                return app(GoalieBehavior::class);
            case self::RIGHT_WING;
                return app(RightWingBehavior::class);
            case self::LEFT_WING;
                return app(LeftWingBehavior::class);
            case self::DEFENSEMAN;
                return app(DefensemanBehavior::class);
            case self::HOCKEY_CENTER;
                return app(HockeyCenterBehavior::class);
        }

        throw new UnknownBehaviorException($this->name, PositionBehavior::class);
    }

    /**
     * @return float|int
     */
    public function getDefaultEssenceCost()
    {
        return $this->getBehavior()->getDefaultEssenceCost();
    }

    /**
     * @return float|int
     */
    public function getMinimumEssenceCost()
    {
        return $this->getBehavior()->getMinimumEssenceCost();
    }

    /**
     * @return int
     */
    public function getGamesPerSeason()
    {
        return $this->getBehavior()->getGamesPerSeason();
    }
}

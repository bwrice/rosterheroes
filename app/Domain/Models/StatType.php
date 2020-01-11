<?php

namespace App\Domain\Models;

use App\Domain\Behaviors\StatTypes\Baseball\BaseOnBallsAgainstBehavior;
use App\Domain\Behaviors\StatTypes\Baseball\BaseOnBallsBehavior;
use App\Domain\Behaviors\StatTypes\Baseball\BasketballAssistBehavior;
use App\Domain\Behaviors\StatTypes\Baseball\BasketballBlockBehavior;
use App\Domain\Behaviors\StatTypes\Baseball\CompleteGameBehavior;
use App\Domain\Behaviors\StatTypes\Baseball\CompleteGameShutoutBehavior;
use App\Domain\Behaviors\StatTypes\Baseball\DoubleBehavior;
use App\Domain\Behaviors\StatTypes\Baseball\EarnedRunAllowedBehavior;
use App\Domain\Behaviors\StatTypes\Baseball\GoalAgainstBehavior;
use App\Domain\Behaviors\StatTypes\Baseball\GoalBehavior;
use App\Domain\Behaviors\StatTypes\Baseball\GoalieSaveBehavior;
use App\Domain\Behaviors\StatTypes\Baseball\GoalieWinBehavior;
use App\Domain\Behaviors\StatTypes\Baseball\HatTrickBehavior;
use App\Domain\Behaviors\StatTypes\Baseball\HitAgainstBehavior;
use App\Domain\Behaviors\StatTypes\Baseball\HitBatsmenBehavior;
use App\Domain\Behaviors\StatTypes\Baseball\HitBehavior;
use App\Domain\Behaviors\StatTypes\Baseball\HitByPitchBehavior;
use App\Domain\Behaviors\StatTypes\Baseball\HockeyAssistBehavior;
use App\Domain\Behaviors\StatTypes\Baseball\HockeyBlockedShotBehavior;
use App\Domain\Behaviors\StatTypes\Baseball\HomeRunBehavior;
use App\Domain\Behaviors\StatTypes\Baseball\PitchingSaveBehavior;
use App\Domain\Behaviors\StatTypes\Baseball\PitchingWinBehavior;
use App\Domain\Behaviors\StatTypes\Baseball\PointMadeBehavior;
use App\Domain\Behaviors\StatTypes\Baseball\RBIBehavior;
use App\Domain\Behaviors\StatTypes\Baseball\ReboundBehavior;
use App\Domain\Behaviors\StatTypes\Baseball\RunScoredBehavior;
use App\Domain\Behaviors\StatTypes\Baseball\ShotOnGoalBehavior;
use App\Domain\Behaviors\StatTypes\Baseball\StealBehavior;
use App\Domain\Behaviors\StatTypes\Baseball\StolenBaseBehavior;
use App\Domain\Behaviors\StatTypes\Baseball\StrikeoutBehavior;
use App\Domain\Behaviors\StatTypes\Baseball\ThreePointerBehavior;
use App\Domain\Behaviors\StatTypes\Baseball\TripleBehavior;
use App\Domain\Behaviors\StatTypes\Baseball\TurnoverBehavior;
use App\Domain\Behaviors\StatTypes\Football\FumbleLostBehavior;
use App\Domain\Behaviors\StatTypes\Football\InterceptionBehavior;
use App\Domain\Behaviors\StatTypes\Football\PassTDBehavior;
use App\Domain\Behaviors\StatTypes\Football\PassYardBehavior;
use App\Domain\Behaviors\StatTypes\Football\ReceptionBehavior;
use App\Domain\Behaviors\StatTypes\Football\RecTDBehavior;
use App\Domain\Behaviors\StatTypes\Football\RecYardBehavior;
use App\Domain\Behaviors\StatTypes\Football\RushTDBehavior;
use App\Domain\Behaviors\StatTypes\Football\RushYardBehavior;
use App\Domain\Behaviors\StatTypes\InningsPitchedCalculator;
use App\Domain\Behaviors\StatTypes\StatTypeBehavior;
use App\Domain\Collections\StatTypeCollection;
use App\Domain\Models\Traits\HasUniqueNames;
use App\Exceptions\UnknownBehaviorException;
use Illuminate\Database\Eloquent\Model;


/**
 * Class StatType
 * @package App\Domain\Models
 *
 * @property int $id
 * @property string $name
 *
 * @method static StatTypeCollection all($columns = ['*'])
 */
class StatType extends Model
{
    use HasUniqueNames;

    public const PASS_TD = 'passing-touchdown';
    public const RUSH_TD = 'rushing-touchdown';
    public const REC_TD = 'receiving-touchdown';
    public const PASS_YARD = 'passing-yard';
    public const RUSH_YARD = 'rushing-yard';
    public const REC_YARD = 'receiving-yard';
    public const RECEPTION = 'reception';
    public const INTERCEPTION = 'interception';
    public const FUMBLE_LOST = 'fumble-lost';

    public const HIT = 'hit';
    public const DOUBLE = 'double';
    public const TRIPLE = 'triple';
    public const HOME_RUN = 'home-run';
    public const RUN_BATTED_IN = 'run-batted-in';
    public const RUN_SCORED = 'run-scored';
    public const BASE_ON_BALLS = 'base-on-balls';
    public const HIT_BY_PITCH = 'hit-by-pitch';
    public const STOLEN_BASE = 'stolen-base';
    public const INNING_PITCHED = 'inning-pitched';
    public const STRIKEOUT = 'strikeout';
    public const PITCHING_WIN = 'pitcher-win';
    public const PITCHING_SAVE = 'pitcher-save';
    public const EARNED_RUN_ALLOWED = 'earned-run-allowed';
    public const HIT_AGAINST = 'hit-against';
    public const BASE_ON_BALLS_AGAINST = 'base-on-balls-against';
    public const HIT_BATSMAN = 'hit-batsman';
    public const COMPLETE_GAME = 'complete-game';
    public const COMPLETE_GAME_SHUTOUT = 'complete-game-shutout';

    public const GOAL = 'goal';
    public const HOCKEY_ASSIST = 'hockey-assist';
    public const SHOT_ON_GOAL = 'shot-on-goal';
    public const HOCKEY_BLOCKED_SHOT = 'blocked-shot';
    public const GOALIE_WIN = 'goalie-win';
    public const GOALIE_SAVE = 'goalie-save';
    public const GOAL_AGAINST = 'goal-against';
    public const HAT_TRICK = 'hat-trick';

    public const POINT_MADE = 'point-made';
    public const THREE_POINTER = 'three-pointer';
    public const REBOUND = 'rebound';
    public const BASKETBALL_ASSIST = 'basketball-assist';
    public const STEAL = 'steal';
    public const BASKETBALL_BLOCK = 'block';
    public const TURNOVER = 'turnover';

    protected $guarded = [];

    public function newCollection(array $models = [])
    {
        return new StatTypeCollection($models);
    }

    public function getBehavior(): StatTypeBehavior
    {
        switch ($this->name) {
            // Football
            case self::PASS_TD:
                return app(PassTDBehavior::class);
            case self::RUSH_TD:
                return app(RushTDBehavior::class);
            case self::REC_TD:
                return app(RecTDBehavior::class);
            case self::PASS_YARD:
                return app(PassYardBehavior::class);
            case self::RUSH_YARD:
                return app(RushYardBehavior::class);
            case self::REC_YARD:
                return app(RecYardBehavior::class);
            case self::RECEPTION:
                return app(ReceptionBehavior::class);
            case self::INTERCEPTION:
                return app(InterceptionBehavior::class);
            case self::FUMBLE_LOST:
                return app(FumbleLostBehavior::class);

            // Baseball
            case self::HIT:
                return app(HitBehavior::class);
            case self::DOUBLE:
                return app(DoubleBehavior::class);
            case self::TRIPLE:
                return app(TripleBehavior::class);
            case self::HOME_RUN:
                return app(HomeRunBehavior::class);
            case self::RUN_BATTED_IN:
                return app(RBIBehavior::class);
            case self::RUN_SCORED:
                return app(RunScoredBehavior::class);
            case self::BASE_ON_BALLS:
                return app(BaseOnBallsBehavior::class);
            case self::HIT_BY_PITCH:
                return app(HitByPitchBehavior::class);
            case self::STOLEN_BASE:
                return app(StolenBaseBehavior::class);
            case self::INNING_PITCHED:
                return app(InningsPitchedCalculator::class);
            case self::STRIKEOUT:
                return app(StrikeoutBehavior::class);
            case self::PITCHING_WIN:
                return app(PitchingWinBehavior::class);
            case self::PITCHING_SAVE:
                return app(PitchingSaveBehavior::class);
            case self::EARNED_RUN_ALLOWED:
                return app(EarnedRunAllowedBehavior::class);
            case self::HIT_AGAINST:
                return app(HitAgainstBehavior::class);
            case self::BASE_ON_BALLS_AGAINST:
                return app(BaseOnBallsAgainstBehavior::class);
            case self::HIT_BATSMAN:
                return app(HitBatsmenBehavior::class);
            case self::COMPLETE_GAME:
                return app(CompleteGameBehavior::class);
            case self::COMPLETE_GAME_SHUTOUT:
                return app(CompleteGameShutoutBehavior::class);

            // Hockey
            case self::GOAL:
                return app(GoalBehavior::class);
            case self::HOCKEY_ASSIST:
                return app(HockeyAssistBehavior::class);
            case self::SHOT_ON_GOAL:
                return app(ShotOnGoalBehavior::class);
            case self::HOCKEY_BLOCKED_SHOT:
                return app(HockeyBlockedShotBehavior::class);
            case self::GOALIE_WIN:
                return app(GoalieWinBehavior::class);
            case self::GOALIE_SAVE:
                return app(GoalieSaveBehavior::class);
            case self::GOAL_AGAINST:
                return app(GoalAgainstBehavior::class);
            case self::HAT_TRICK:
                return app(HatTrickBehavior::class);

            // Basketball
            case self::POINT_MADE:
                return app(PointMadeBehavior::class);
            case self::THREE_POINTER:
                return app(ThreePointerBehavior::class);
            case self::REBOUND:
                return app(ReboundBehavior::class);
            case self::BASKETBALL_ASSIST:
                return app(BasketballAssistBehavior::class);
            case self::STEAL:
                return app(StealBehavior::class);
            case self::BASKETBALL_BLOCK:
                return app(BasketballBlockBehavior::class);
            case self::TURNOVER:
                return app(TurnoverBehavior::class);
        }

        throw new UnknownBehaviorException($this->name, StatTypeBehavior::class);
    }

    public function getDescription()
    {
        return ucwords(str_replace('-',' ', $this->name)) . ' [' . $this->getBehavior()->getPointsPer() . ']';
    }
}

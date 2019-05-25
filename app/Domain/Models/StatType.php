<?php

namespace App\Domain\Models;

use App\Domain\Behaviors\StatTypes\InningsPitchedCalculator;
use App\Domain\Behaviors\StatTypes\MultiplierCalculator;
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
    public const PITCHING_WIN = 'pitching-win';
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
    public const SAVE = 'save';
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
                return new StatTypeBehavior(new MultiplierCalculator(4));
            case self::RUSH_TD:
                return new StatTypeBehavior(new MultiplierCalculator(6));
            case self::REC_TD:
                return new StatTypeBehavior(new MultiplierCalculator(6));
            case self::PASS_YARD:
                return new StatTypeBehavior(new MultiplierCalculator(.04));
            case self::RUSH_YARD:
                return new StatTypeBehavior(new MultiplierCalculator(0.1));
            case self::REC_YARD:
                return new StatTypeBehavior(new MultiplierCalculator(0.1));
            case self::RECEPTION:
                return new StatTypeBehavior(new MultiplierCalculator(.5));
            case self::INTERCEPTION:
                return new StatTypeBehavior(new MultiplierCalculator(-1));
            case self::FUMBLE_LOST:
                return new StatTypeBehavior(new MultiplierCalculator(-2));

            // Baseball
            case self::HIT:
                return new StatTypeBehavior(new MultiplierCalculator(3));
            case self::DOUBLE:
                return new StatTypeBehavior(new MultiplierCalculator(2));
            case self::TRIPLE:
                return new StatTypeBehavior(new MultiplierCalculator(5));
            case self::HOME_RUN:
                return new StatTypeBehavior(new MultiplierCalculator(7));
            case self::RUN_BATTED_IN:
                return new StatTypeBehavior(new MultiplierCalculator(2));
            case self::RUN_SCORED:
                return new StatTypeBehavior(new MultiplierCalculator(2));
            case self::BASE_ON_BALLS:
                return new StatTypeBehavior(new MultiplierCalculator(2));
            case self::HIT_BY_PITCH:
                return new StatTypeBehavior(new MultiplierCalculator(2));
            case self::STOLEN_BASE:
                return new StatTypeBehavior(new MultiplierCalculator(4));
            case self::INNING_PITCHED:
                return new StatTypeBehavior(new InningsPitchedCalculator(new MultiplierCalculator(3)));
            case self::STRIKEOUT:
                return new StatTypeBehavior(new MultiplierCalculator(2.5));
            case self::PITCHING_WIN:
                return new StatTypeBehavior(new MultiplierCalculator(4));
            case self::EARNED_RUN_ALLOWED:
                return new StatTypeBehavior(new MultiplierCalculator(-2));
            case self::HIT_AGAINST:
                return new StatTypeBehavior(new MultiplierCalculator(-.75));
            case self::BASE_ON_BALLS_AGAINST:
                return new StatTypeBehavior(new MultiplierCalculator(-.75));
            case self::HIT_BATSMAN:
                return new StatTypeBehavior(new MultiplierCalculator(-.75));
            case self::COMPLETE_GAME:
                return new StatTypeBehavior(new MultiplierCalculator(3));
            case self::COMPLETE_GAME_SHUTOUT:
                return new StatTypeBehavior(new MultiplierCalculator(5));

            // Hockey
            case self::GOAL:
                return new StatTypeBehavior(new MultiplierCalculator(12));
            case self::HOCKEY_ASSIST:
                return new StatTypeBehavior(new MultiplierCalculator(7));
            case self::SHOT_ON_GOAL:
                return new StatTypeBehavior(new MultiplierCalculator(3));
            case self::HOCKEY_BLOCKED_SHOT:
                return new StatTypeBehavior(new MultiplierCalculator(1.5));
            case self::GOALIE_WIN:
                return new StatTypeBehavior(new MultiplierCalculator(7));
            case self::SAVE:
                return new StatTypeBehavior(new MultiplierCalculator(.75));
            case self::GOAL_AGAINST:
                return new StatTypeBehavior(new MultiplierCalculator(-1.5));
            case self::HAT_TRICK:
                return new StatTypeBehavior(new MultiplierCalculator(5));

            // Basketball
            case self::POINT_MADE:
                return new StatTypeBehavior(new MultiplierCalculator(.75));
            case self::THREE_POINTER:
                return new StatTypeBehavior(new MultiplierCalculator(.3));
            case self::REBOUND:
                return new StatTypeBehavior(new MultiplierCalculator(1));
            case self::BASKETBALL_ASSIST:
                return new StatTypeBehavior(new MultiplierCalculator(1));
            case self::STEAL:
                return new StatTypeBehavior(new MultiplierCalculator(1.25));
            case self::BASKETBALL_BLOCK:
                return new StatTypeBehavior(new MultiplierCalculator(1.25));
            case self::TURNOVER:
                return new StatTypeBehavior(new MultiplierCalculator(-.25));
        }

        throw new UnknownBehaviorException($this->name, StatTypeBehavior::class);
    }
}

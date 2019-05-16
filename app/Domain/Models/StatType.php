<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * Class StatType
 * @package App\Domain\Models
 *
 * @property int $id
 */
class StatType extends Model
{
    public const PASS_TD = 'passing-touchdown';
    public const RUSH_TD = 'rushing-touchdown';
    public const REC_TD = 'receiving-touchdown';
    public const PASS_YARD = 'passing-yard';
    public const RUSH_YARD = 'rushing-yard';
    public const REC_YARD = 'receiving-yard';
    public const RECEPTION = 'reception';
    public const INT = 'interception';
    public const FUMBLE = 'fumble-lost';

    public const SINGLE = 'single';
    public const DOUBLE = 'double';
    public const TRIPLE = 'triple';
    public const HR = 'home-run';
    public const BB = 'base-on-balls';
    public const HBP = 'hit-by-pitch';
    public const SB = 'stolen-base';
    public const INNING_PITCHED = 'inning-pitched';
    public const K = 'strikeout';
    public const PITCHING_WIN = 'pitching-win';
    public const EARNED_RUN_ALLOWED = 'earned-run-allowed';
    public const HIT_AGAINST = 'hit-against';
    public const BASE_ON_BALLS_AGAINST = 'base-on-balls-against';
    public const HIT_BATSMAN = 'hit-batsman';
    public const COMPLETE_GAME = 'complete-game';
    public const COMPLETE_GAME_SHUTOUT = 'complete-game-shutout';
    public const NO_HITTER = 'no-hitter';

    public const GOAL = 'goal';
    public const HOCKEY_ASSIST = 'hockey-assist';
    public const SHOT_ON_GOAL = 'shot-on-goal';
    public const BLOCKED_SHOT = 'blocked-shot';
    public const GOALIE_WIN = 'goalie-win';
    public const SAVE = 'save';
    public const GOAL_AGAINST = 'goal-against';
    public const HAT_TRICK = 'hat-trick';

    public const POINT = 'point';
    public const THREE_POINTER = 'three-pointer';
    public const REBOUND = 'rebound';
    public const BASKETBALL_ASSIST = 'basketball-assist';
    public const STEAL = 'steal';
    public const BLOCK = 'block';
    public const TURNOVER = 'turnover';

    protected $guarded = [];
}

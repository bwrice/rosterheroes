<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 5/11/19
 * Time: 12:33 PM
 */

namespace App\Domain\Enums;

use App\Domain\Enums\Sports\Sport;
use App\Domain\Enums\StatTypes\StatType;
use App\Exceptions\UnknownEnumException;


class Enum
{
    /**
     * @param $key
     * @return \App\Domain\Enums\StatTypes\StatType
     * @throws \Exception
     */
    public function statType(string $key): StatType
    {
        switch ($key) {

            case StatType::PASS_TD:
            case StatType::RUSH_TD:
            case StatType::REC_TD:
            case StatType::PASS_YARD:
            case StatType::RUSH_YARD:
            case StatType::REC_YARD:
            case StatType::RECEPTION:
            case StatType::INT:
            case StatType::FUMBLE:

            case StatType::SINGLE:
            case StatType::DOUBLE:
            case StatType::TRIPLE:
            case StatType::HR:
            case StatType::BB:
            case StatType::HBP:
            case StatType::SB:
            case StatType::INNING_PITCHED:
            case StatType::K:
            case StatType::PITCHING_WIN:
            case StatType::EARNED_RUN_ALLOWED:
            case StatType::HIT_AGAINST:
            case StatType::BASE_ON_BALLS_AGAINST:
            case StatType::HIT_BATSMAN:
            case StatType::COMPLETE_GAME:
            case StatType::COMPLETE_GAME_SHUTOUT:
            case StatType::NO_HITTER:

            case StatType::GOAL:
            case StatType::HOCKEY_ASSIST:
            case StatType::SHOT_ON_GOAL:
            case StatType::BLOCKED_SHOT:
            case StatType::GOALIE_WIN:
            case StatType::SAVE:
            case StatType::GOAL_AGAINST:
            case StatType::HAT_TRICK:

            case StatType::POINT:
            case StatType::THREE_POINTER:
            case StatType::REBOUND:
            case StatType::BASKETBALL_ASSIST:
            case StatType::STEAL:
            case StatType::BLOCK:
            case StatType::TURNOVER:
        }
        throw new UnknownEnumException(StatType::class, $key);
    }

    public function sport($key): Sport
    {
        switch ($key) {
            case Sport::FOOTBALL:
                return new Sport($key);
            case SPORT::BASEBALL:
                return new Sport($key);
            case Sport::BASKETBALL:
                return new Sport($key);
            case SPORT::HOCKEY:
                return new Sport($key);
        }
        throw new UnknownEnumException(Sport::class, $key);
    }
}
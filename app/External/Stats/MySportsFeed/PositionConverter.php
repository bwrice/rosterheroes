<?php


namespace App\External\Stats\MySportsFeed;

use App\Domain\Models\League;
use App\Domain\Models\Position;

class PositionConverter
{
    public function convertAbbreviationToPositionName(string $abbreviation, string $leagueAbbreviation)
    {
        switch ($abbreviation) {
            case 'QB':
                return Position::QUARTERBACK;
            case 'RB':
                return Position::RUNNING_BACK;
            case 'WR':
                return Position::WIDE_RECEIVER;
            case 'TE':
                return Position::TIGHT_END;
            case 'P':
                return Position::PITCHER;
            case '1B':
                return Position::FIRST_BASE;
            case '2B':
                return Position::SECOND_BASE;
            case '3B':
                return Position::THIRD_BASE;
            case 'SS':
                return Position::SHORTSTOP;
            case 'LF':
            case 'RF':
            case 'CF':
                return Position::OUTFIELD;
            case 'SF':
                return Position::SMALL_FORWARD;
            case 'PF':
                return Position::POWER_FORWARD;
            case 'SG':
                return Position::SHOOTING_GUARD;
            case 'PG':
                return Position::POINT_GUARD;
            case 'LW':
                return Position::LEFT_WING;
            case 'RW':
                return Position::RIGHT_WING;
            case 'D':
                return Position::DEFENSEMAN;
            case 'G':
                return Position::GOALIE;
            case 'C':
                if ($leagueAbbreviation === League::MLB) {
                    return Position::CATCHER;
                } elseif ($leagueAbbreviation === League::NHL) {
                    return Position::HOCKEY_CENTER;
                } else {
                    return Position::BASKETBALL_CENTER;
                }
        }
        return '';
    }

    public function convertPositionNameIntoAbbreviations(string $positionName)
    {
        switch($positionName) {
            case Position::QUARTERBACK:
                return ['QB'];
            case Position::RUNNING_BACK:
                return ['RB'];
            case Position::WIDE_RECEIVER:
                return ['WR'];
            case Position::TIGHT_END:
                return ['TE'];
            case Position::FIRST_BASE:
                return ['1B'];
            case Position::SECOND_BASE:
                return ['2B'];
            case Position::THIRD_BASE:
                return ['3B'];
            case Position::SHORTSTOP:
                return ['SS'];
            case Position::PITCHER:
                return ['P'];
            case Position::OUTFIELD:
                return ['LF', 'RF', 'CF'];
            case Position::POINT_GUARD:
                return ['PG'];
            case Position::SHOOTING_GUARD:
                return ['SG'];
            case Position::SMALL_FORWARD:
                return ['SF'];
            case Position::POWER_FORWARD:
                return ['PF'];
            case Position::LEFT_WING:
                return ['LW'];
            case Position::RIGHT_WING:
                return ['RW'];
            case Position::DEFENSEMAN:
                return ['D'];
            case Position::GOALIE:
                return ['G'];
            case Position::CATCHER:
            case Position::HOCKEY_CENTER:
            case Position::BASKETBALL_CENTER:
                return ['C'];
            default:
                return [];
        }
    }
}

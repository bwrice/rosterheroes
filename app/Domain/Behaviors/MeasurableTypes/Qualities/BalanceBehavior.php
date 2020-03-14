<?php


namespace App\Domain\Behaviors\MeasurableTypes\Qualities;


use App\Domain\Models\MeasurableType;
use App\Domain\Models\StatType;

class BalanceBehavior extends QualityBehavior
{
    protected $statTypeNames = [
        StatType::RUSH_TD,
        StatType::PASS_YARD,
        StatType::DOUBLE,
        StatType::INNING_PITCHED,
        StatType::EARNED_RUN_ALLOWED,
        StatType::GOALIE_SAVE,
        StatType::GOAL_AGAINST,
        StatType::BASKETBALL_ASSIST,
    ];

    public function __construct()
    {
        parent::__construct();
        $this->name = MeasurableType::BALANCE;
    }
}

<?php


namespace App\Domain\Behaviors\MeasurableTypes\Qualities;


use App\Domain\Models\MeasurableType;
use App\Domain\Models\StatType;

class WrathBehavior extends QualityBehavior
{
    protected $statTypeNames = [
        StatType::REC_YARD,
        StatType::HOME_RUN,
        StatType::RUN_SCORED,
        StatType::PITCHING_WIN,
        StatType::PITCHING_SAVE,
        StatType::HIT_AGAINST,
        StatType::SHOT_ON_GOAL,
        StatType::STEAL,
        StatType::TURNOVER,
    ];

    public function __construct()
    {
        parent::__construct();
        $this->name = MeasurableType::WRATH;
    }
}

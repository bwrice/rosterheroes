<?php


namespace App\Domain\Behaviors\MeasurableTypes\Qualities;


use App\Domain\Models\MeasurableType;
use App\Domain\Models\StatType;

class PrestigeBehavior extends QualityBehavior
{
    protected $statTypeNames = [
        StatType::REC_TD,
        StatType::RUN_BATTED_IN,
        StatType::BASE_ON_BALLS,
        StatType::HIT_BY_PITCH,
        StatType::COMPLETE_GAME,
        StatType::COMPLETE_GAME_SHUTOUT,
        StatType::GOAL,
        StatType::HOCKEY_BLOCKED_SHOT,
        StatType::REBOUND,
    ];

    public function __construct()
    {
        parent::__construct();
        $this->name = MeasurableType::PRESTIGE;
    }
}

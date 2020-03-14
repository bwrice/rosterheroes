<?php


namespace App\Domain\Behaviors\MeasurableTypes\Qualities;


use App\Domain\Models\MeasurableType;
use App\Domain\Models\StatType;

class HonorBehavior extends QualityBehavior
{
    protected $statTypeNames = [
        StatType::RUSH_YARD,
        StatType::FUMBLE_LOST,
        StatType::HIT,
        StatType::STRIKEOUT,
        StatType::BASE_ON_BALLS_AGAINST,
        StatType::HIT_BATSMAN,
        StatType::HOCKEY_ASSIST,
        StatType::POINT_MADE,
    ];

    public function __construct()
    {
        parent::__construct();
        $this->name = MeasurableType::HONOR;
    }
}

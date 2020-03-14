<?php


namespace App\Domain\Behaviors\MeasurableTypes\Qualities;


use App\Domain\Models\MeasurableType;
use App\Domain\Models\StatType;

class PassionBehavior extends QualityBehavior
{
    protected $statTypeNames = [
        StatType::PASS_TD,
        StatType::RECEPTION,
        StatType::INTERCEPTION,
        StatType::STOLEN_BASE,
        StatType::TRIPLE,
        StatType::GOALIE_WIN,
        StatType::HAT_TRICK,
        StatType::THREE_POINTER,
        StatType::BASKETBALL_BLOCK,
    ];

    public function __construct()
    {
        parent::__construct();
        $this->name = MeasurableType::PASSION;
    }
}

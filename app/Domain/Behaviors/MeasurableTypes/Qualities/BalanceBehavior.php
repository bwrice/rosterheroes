<?php


namespace App\Domain\Behaviors\MeasurableTypes\Qualities;


use App\Domain\Models\MeasurableType;
use App\Domain\Models\StatType;

class BalanceBehavior extends QualityBehavior
{
    public function __construct()
    {
        parent::__construct();
        $this->name = MeasurableType::BALANCE;
    }
}

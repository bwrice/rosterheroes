<?php


namespace App\Domain\Behaviors\MeasurableTypes\Qualities;


use App\Domain\Models\MeasurableType;

class WrathBehavior extends QualityBehavior
{

    public function __construct()
    {
        parent::__construct();
        $this->name = MeasurableType::WRATH;
    }
}

<?php


namespace App\Domain\Behaviors\MeasurableTypes\Attributes;


use App\Domain\Models\MeasurableType;

class StrengthBehavior extends AttributeBehavior
{
    public function __construct()
    {
        parent::__construct();
        $this->name = MeasurableType::STRENGTH;
    }
}

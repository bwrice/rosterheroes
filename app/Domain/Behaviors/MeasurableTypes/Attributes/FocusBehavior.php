<?php


namespace App\Domain\Behaviors\MeasurableTypes\Attributes;


use App\Domain\Models\MeasurableType;

class FocusBehavior extends AttributeBehavior
{

    public function __construct()
    {
        parent::__construct();
        $this->name = MeasurableType::FOCUS;
    }
}

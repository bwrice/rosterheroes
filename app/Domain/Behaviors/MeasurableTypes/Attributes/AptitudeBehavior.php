<?php


namespace App\Domain\Behaviors\MeasurableTypes\Attributes;


use App\Domain\Models\MeasurableType;

class AptitudeBehavior extends AttributeBehavior
{

    public function __construct()
    {
        parent::__construct();
        $this->name = MeasurableType::APTITUDE;
    }
}

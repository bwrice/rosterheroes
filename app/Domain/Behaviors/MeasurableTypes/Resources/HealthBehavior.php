<?php


namespace App\Domain\Behaviors\MeasurableTypes\Resources;


use App\Domain\Models\MeasurableType;

class HealthBehavior extends ResourceBehavior
{
    public function __construct()
    {
        parent::__construct();
        $this->name = MeasurableType::HEALTH;
    }
}

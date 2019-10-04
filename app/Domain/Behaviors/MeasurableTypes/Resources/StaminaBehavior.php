<?php


namespace App\Domain\Behaviors\MeasurableTypes\Resources;


use App\Domain\Models\MeasurableType;

class StaminaBehavior extends ResourceBehavior
{
    public function __construct()
    {
        parent::__construct();
        $this->name = MeasurableType::STAMINA;
    }

    public function getBaseAmount(): int
    {
        return 200;
    }
}

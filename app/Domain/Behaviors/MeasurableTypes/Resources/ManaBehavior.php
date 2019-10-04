<?php


namespace App\Domain\Behaviors\MeasurableTypes\Resources;


use App\Domain\Models\MeasurableType;

class ManaBehavior extends ResourceBehavior
{
    public function __construct()
    {
        parent::__construct();
        $this->name = MeasurableType::MANA;
    }

    public function getBaseAmount(): int
    {
        return 200;
    }
}

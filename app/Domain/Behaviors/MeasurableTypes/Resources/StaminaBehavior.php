<?php


namespace App\Domain\Behaviors\MeasurableTypes\Resources;


class StaminaBehavior extends ResourceBehavior
{

    public function getBaseAmount(): int
    {
        return 200;
    }
}

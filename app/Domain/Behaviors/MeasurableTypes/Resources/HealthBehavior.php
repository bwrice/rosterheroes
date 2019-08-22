<?php


namespace App\Domain\Behaviors\MeasurableTypes\Resources;


class HealthBehavior extends ResourceBehavior
{

    public function getBaseAmount(): int
    {
        return 400;
    }
}

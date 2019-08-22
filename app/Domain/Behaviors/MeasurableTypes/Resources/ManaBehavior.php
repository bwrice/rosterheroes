<?php


namespace App\Domain\Behaviors\MeasurableTypes\Resources;


class ManaBehavior extends ResourceBehavior
{

    public function getBaseAmount(): int
    {
        return 200;
    }
}

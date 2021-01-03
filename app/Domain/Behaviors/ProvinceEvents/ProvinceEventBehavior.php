<?php


namespace App\Domain\Behaviors\ProvinceEvents;


abstract class ProvinceEventBehavior
{
    protected array $extra;

    public function __construct(array $extra)
    {
        $this->extra = $extra;
    }
}

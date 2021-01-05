<?php


namespace App\Domain\Behaviors\ProvinceEvents;


use App\Domain\Models\ProvinceEvent;

abstract class ProvinceEventBehavior
{
    protected array $extra;

    public function __construct(array $extra)
    {
        $this->extra = $extra;
    }

    abstract public function getSupplementalResourceData(ProvinceEvent $provinceEvent): array;
}

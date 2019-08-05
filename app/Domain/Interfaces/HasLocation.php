<?php


namespace App\Domain\Interfaces;


use App\Domain\Models\Province;

interface HasLocation
{
    public function getCurrentLocation(): Province;

    public function updateLocation(Province $newLocation);
}

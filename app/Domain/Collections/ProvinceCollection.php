<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 1/20/19
 * Time: 5:32 PM
 */

namespace App\Domain\Collections;


use App\Domain\Models\Province;
use Illuminate\Database\Eloquent\Collection;

class ProvinceCollection extends Collection
{
    public function toUuids()
    {
        return $this->map(function(Province $position) {
            return $position->uuid;
        })->values()->toArray();
    }
}

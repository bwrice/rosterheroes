<?php


namespace App\Domain\Actions;


use App\Domain\Models\Province;
use App\Domain\Models\Squad;
use App\Exceptions\NotBorderedByException;

class BorderTravelAction
{
    public function execute(Squad $squad, Province $border)
    {
        if(! $squad->province->isBorderedBy($border)) {
            throw (new NotBorderedByException())->setProvinces($squad->province, $border);
        }
    }
}

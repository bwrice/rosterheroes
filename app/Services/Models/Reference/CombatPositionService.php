<?php


namespace App\Services\Models\Reference;


use App\Domain\Models\CombatPosition;
use Illuminate\Database\Eloquent\Collection;

class CombatPositionService extends ReferenceService
{

    protected function all(): Collection
    {
        return CombatPosition::all();
    }
}

<?php


namespace App\Services\Models\Reference;


use App\Domain\Models\TargetPriority;
use Illuminate\Database\Eloquent\Collection;

class TargetPriorityService extends ReferenceService
{

    protected function all(): Collection
    {
        return TargetPriority::all();
    }
}

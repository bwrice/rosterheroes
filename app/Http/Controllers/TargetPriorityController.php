<?php

namespace App\Http\Controllers;

use App\Domain\Models\TargetPriority;
use App\Http\Resources\TargetPriorityResource;
use Illuminate\Support\Facades\Cache;

class TargetPriorityController extends Controller
{
    public function index()
    {
        $targetPriorities = Cache::remember('all_target_priorities', 60 * 60 * 24, function () {
            return TargetPriority::all();
        });

        return TargetPriorityResource::collection($targetPriorities);
    }
}

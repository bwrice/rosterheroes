<?php

namespace App\Http\Controllers;

use App\Domain\Models\Squad;
use App\Policies\SquadPolicy;
use Illuminate\Http\Request;

class SquadSnapshotController extends Controller
{
    public function show(string $squadSlug, int $weekID)
    {
        $squad = Squad::findSlugOrFail($squadSlug);
        $this->authorize(SquadPolicy::MANAGE, $squad);
    }
}

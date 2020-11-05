<?php

namespace App\Http\Controllers;

use App\Domain\Models\Squad;
use App\Domain\Models\SquadSnapshot;
use App\Http\Resources\SquadSnapshotResource;
use App\Policies\SquadPolicy;
use Illuminate\Http\Request;

class SquadSnapshotController extends Controller
{
    public function show(string $squadSlug, int $weekID)
    {
        $squad = Squad::findSlugOrFail($squadSlug);
        $this->authorize(SquadPolicy::MANAGE, $squad);

        $squadSnapshot = SquadSnapshot::query()
            ->where('squad_id', '=', $squad->id)
            ->where('week_id', '=', $weekID)
            ->firstOrFail();

        $squadSnapshot->load(SquadSnapshot::resourceRelations());

        return new SquadSnapshotResource($squadSnapshot);
    }
}

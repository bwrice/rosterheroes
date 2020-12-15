<?php

namespace App\Http\Resources;

use App\Domain\Models\MinionSnapshot;
use App\Domain\Models\SideQuestSnapshot;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class SideQuestSnapshotResource
 * @package App\Http\Resources
 *
 * @mixin SideQuestSnapshot
 */
class SideQuestSnapshotResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'uuid' => $this->uuid,
            'weekID' => $this->week_id,
            'name' => $this->buildName(),
            'minionSnapshots' => $this->minionSnapshots->map(function (MinionSnapshot $minionSnapshot) {
                $resource = new MinionSnapshotResource($minionSnapshot);
                return $resource->setCount($minionSnapshot->pivot->count);
            })
        ];
    }
}

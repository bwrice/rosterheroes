<?php


namespace App\Domain\Actions\NPC;


use App\Domain\Actions\FindPathBetweenProvinces;
use App\Domain\Actions\SquadFastTravelAction;
use App\Domain\Models\Province;
use App\Domain\Models\Squad;

/**
 * Class MoveNPCToProvince
 * @package App\Domain\Actions\NPC
 *
 * @method execute(Squad $squad, Province $province)
 */
class MoveNPCToProvince extends NPCAction
{
    protected FindPathBetweenProvinces $findPathBetweenProvinces;
    protected SquadFastTravelAction $fastTravelAction;

    public function __construct(FindPathBetweenProvinces $findPathBetweenProvinces, SquadFastTravelAction $fastTravelAction)
    {
        $this->findPathBetweenProvinces = $findPathBetweenProvinces;
        $this->fastTravelAction = $fastTravelAction;
    }

    protected function handleExecute(Province $province)
    {
        // If our npc is already at the province, we don't need to travel
        if ($this->npc->province_id === $province->id) {
            return $this->npc;
        }

        $route = $this->findPathBetweenProvinces->execute($this->npc->province, $province);
        // We can remove the first location since it's the squad's current location
        $route->shift();
        $this->fastTravelAction->execute($this->npc, $route);
        return $this->npc->fresh();
    }
}

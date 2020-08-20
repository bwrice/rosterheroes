<?php


namespace App\Domain\Actions\NPC;


use App\Domain\Actions\FindPathBetweenProvinces;
use App\Domain\Actions\JoinQuestAction;
use App\Domain\Actions\SquadFastTravelAction;
use App\Domain\Models\Quest;
use App\Domain\Models\Squad;
use App\Facades\NPC;

class JoinQuestForNPCOld
{
    /**
     * @var FindPathBetweenProvinces
     */
    protected $findPathBetweenProvinces;
    /**
     * @var SquadFastTravelAction
     */
    protected $fastTravelAction;
    /**
     * @var JoinQuestAction
     */
    protected $joinQuestAction;

    public function __construct(
        FindPathBetweenProvinces $findPathBetweenProvinces,
        SquadFastTravelAction $fastTravelAction,
        JoinQuestAction $joinQuestAction)
    {
        $this->findPathBetweenProvinces = $findPathBetweenProvinces;
        $this->fastTravelAction = $fastTravelAction;
        $this->joinQuestAction = $joinQuestAction;
    }

    public const EXCEPTION_CODE_NOT_NPC = 1;

    public function execute(Squad $squad, Quest $quest)
    {
        if (! NPC::isNPC($squad)) {
            throw new \Exception("Squad: " . $squad->name . " is not an NPC", self::EXCEPTION_CODE_NOT_NPC);
        }

        // If our npc isn't at the quest location, we need to travel there
        if ($squad->province_id !== $quest->province_id) {
            $squad = $this->travelToQuestLocation($squad, $quest);
        }

        $this->joinQuestAction->execute($squad, $quest);


    }

    protected function travelToQuestLocation(Squad $squad, Quest $quest)
    {
        $route = $this->findPathBetweenProvinces->execute($squad->province, $quest->province);
        // We can remove the first location since it's the squad's current location
        $route->shift();
        $this->fastTravelAction->execute($squad, $route);
        return $squad->fresh();
    }
}

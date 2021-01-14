<?php


namespace App\Domain\Actions\NPC;


use App\Domain\Actions\NPC\ActionTriggers\NPCActionTrigger;
use App\Domain\Models\Squad;

/**
 * Class BuildNPCActionTrigger
 * @package App\Domain\Actions\NPC
 *
 * @method NPCActionTrigger execute(Squad $squad, float $baseTriggerChance)
 */
class BuildNPCActionTrigger extends NPCAction
{
    /**
     * @param float $baseTriggerChance
     * @return NPCActionTrigger
     */
    public function handleExecute(float $baseTriggerChance)
    {
        $actionTrigger = new NPCActionTrigger($baseTriggerChance);
        $actionTrigger = $this->updateTriggerForOpenChestAction($actionTrigger);
        return $actionTrigger;
    }

    /**
     * @param NPCActionTrigger $npcActionTrigger
     * @return NPCActionTrigger
     */
    protected function updateTriggerForOpenChestAction(NPCActionTrigger $npcActionTrigger)
    {
        $unopenedChests = $this->npc->chests()
            ->where('opened_at', '=', null)
            ->get();

        if ($unopenedChests->isEmpty()) {
            return $npcActionTrigger;
        }
        $trigger = (rand(1, 100) <= $npcActionTrigger->getTriggerChance());

        if (! $trigger) {
            return $npcActionTrigger;
        }

        $capacityCount = (int) ceil($this->npc->getAvailableCapacity()/50);
        $chestsCount = (int) min($unopenedChests->count(), $capacityCount);

        return $npcActionTrigger->pushAction(NPCActionTrigger::KEY_OPEN_CHESTS, [
            'chests' => $unopenedChests->take($chestsCount)
        ]);
    }
}

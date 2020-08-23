<?php


namespace App\Domain\Actions\NPC;


use App\Domain\Actions\JoinQuestAction;
use App\Domain\Models\Quest;

class JoinQuestForNPC extends NPCAction
{
    /**
     * @var JoinQuestAction
     */
    private $joinQuestAction;

    public function __construct(JoinQuestAction $joinQuestAction)
    {
        $this->joinQuestAction = $joinQuestAction;
    }

    public function handleExecute(Quest $quest)
    {
        return $this->joinQuestAction->execute($this->npc, $quest);
    }
}

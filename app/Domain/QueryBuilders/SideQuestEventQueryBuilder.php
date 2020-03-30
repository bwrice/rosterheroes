<?php


namespace App\Domain\QueryBuilders;


use App\SideQuestEvent;
use Illuminate\Database\Eloquent\Builder;

class SideQuestEventQueryBuilder extends Builder
{
    /**
     * @return SideQuestEvent|null
     */
    public function victoryEvent()
    {
        /** @var SideQuestEvent|null $event */
        $event = $this->where('event_type', '=', SideQuestEvent::TYPE_SIDE_QUEST_VICTORY)->first();
        return $event;
    }

    public function heroKillsMinion()
    {
        return $this->where('event_type', '=', SideQuestEvent::TYPE_HERO_KILLS_MINION);
    }
}

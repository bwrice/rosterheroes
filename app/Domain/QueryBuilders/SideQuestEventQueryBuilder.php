<?php


namespace App\Domain\QueryBuilders;


use App\Domain\Models\SideQuestEvent;
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

    /**
     * @return SideQuestEvent
     */
    public function finalEvent()
    {/** @var SideQuestEvent|null $event */
        $event = $this->orderByDesc('moment')->first();
        return $event;
    }

    public function heroKillsMinion()
    {
        return $this->where('event_type', '=', SideQuestEvent::TYPE_HERO_KILLS_MINION);
    }
}

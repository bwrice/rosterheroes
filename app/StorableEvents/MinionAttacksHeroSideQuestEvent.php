<?php


namespace App\StorableEvents;


abstract class MinionAttacksHeroSideQuestEvent extends StorableSideQuestEvent
{
    /**
     * @return string
     */
    public function getHeroUuid()
    {
        return $this->data['combatHero']['heroUuid'];
    }
}
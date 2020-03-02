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

    public function getDamage()
    {
        if (array_key_exists('damage', $this->data)) {
            return $this->data['damage'];
        }
        return 0;
    }
}

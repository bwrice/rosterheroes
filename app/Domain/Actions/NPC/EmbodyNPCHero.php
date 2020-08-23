<?php


namespace App\Domain\Actions\NPC;


use App\Domain\Actions\AddSpiritToHeroAction;
use App\Facades\NPC;

class EmbodyNPCHero extends NPCHeroAction
{
    /**
     * @var AddSpiritToHeroAction
     */
    protected $addSpiritToHeroAction;

    public function __construct(AddSpiritToHeroAction $addSpiritToHeroAction)
    {
        $this->addSpiritToHeroAction = $addSpiritToHeroAction;
    }

    public function handleExecute()
    {
        $playerSpirit = NPC::heroSpirit($this->hero);
        $this->addSpiritToHeroAction->execute($this->hero, $playerSpirit);
    }
}

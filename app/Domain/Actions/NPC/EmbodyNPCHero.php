<?php


namespace App\Domain\Actions\NPC;


use App\Domain\Actions\AddSpiritToHeroAction;
use App\Domain\Models\Hero;
use App\Domain\Models\PlayerSpirit;
use App\Facades\NPC;

/**
 * Class EmbodyNPCHero
 * @package App\Domain\Actions\NPC
 *
 * @method execute(Hero $hero, PlayerSpirit $playerSpirit)
 */
class EmbodyNPCHero extends NPCHeroAction
{
    protected AddSpiritToHeroAction $addSpiritToHeroAction;

    public function __construct(AddSpiritToHeroAction $addSpiritToHeroAction)
    {
        $this->addSpiritToHeroAction = $addSpiritToHeroAction;
    }

    public function handleExecute(PlayerSpirit $playerSpirit)
    {
        $this->addSpiritToHeroAction->execute($this->hero, $playerSpirit);
    }
}

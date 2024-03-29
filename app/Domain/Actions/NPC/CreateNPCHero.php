<?php


namespace App\Domain\Actions\NPC;


use App\Domain\Actions\AddNewHeroToSquadAction;
use App\Domain\Models\HeroClass;
use App\Domain\Models\HeroRace;
use App\Domain\Models\Squad;
use App\Facades\NPC;

class CreateNPCHero extends NPCAction
{
    /**
     * @var AddNewHeroToSquadAction
     */
    private $addNewHeroToSquadAction;

    public function __construct(AddNewHeroToSquadAction $addNewHeroToSquadAction)
    {
        $this->addNewHeroToSquadAction = $addNewHeroToSquadAction;
    }

    public const EXCEPTION_CODE_INVALID_NPC = 1;
    public const EXCEPTION_CODE_SQUAD_INVALID_STATE = 2;

    /**
     * @param Squad $squad
     * @param HeroRace $heroRace
     * @param HeroClass $heroClass
     * @return \App\Domain\Models\Hero
     * @throws \Exception
     */
    public function handleExecute(HeroRace $heroRace, HeroClass $heroClass)
    {
        if (! $this->npc->inCreationState()) {
            throw new \Exception("NPC Squad: " . $this->npc->name . " cannot create hero when not in creation state", self::EXCEPTION_CODE_SQUAD_INVALID_STATE);
        }

        $heroName = NPC::heroName($this->npc);
        return $this->addNewHeroToSquadAction->execute($this->npc, $heroName, $heroClass, $heroRace);
    }
}

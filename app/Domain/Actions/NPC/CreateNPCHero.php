<?php


namespace App\Domain\Actions\NPC;


use App\Domain\Actions\CreateHeroAction;
use App\Domain\Models\HeroClass;
use App\Domain\Models\HeroRace;
use App\Domain\Models\HeroRank;
use App\Domain\Models\Squad;
use App\Facades\NPC;

class CreateNPCHero
{
    /**
     * @var CreateHeroAction
     */
    private $createHeroAction;

    public function __construct(CreateHeroAction $createHeroAction)
    {
        $this->createHeroAction = $createHeroAction;
    }

    public const EXCEPTION_CODE_INVALID_NPC = 1;
    public const EXCEPTION_CODE_SQUAD_INVALID_STATE = 2;

    public function execute(Squad $squad, HeroRace $heroRace, HeroClass $heroClass)
    {
        if (! NPC::isNPC($squad)) {
            throw new \Exception($squad->name . " is not an NPC Squad", self::EXCEPTION_CODE_INVALID_NPC);
        }
        if (! $squad->inCreationState()) {
            throw new \Exception("NPC Squad: " . $squad->name . " cannot create hero when not in creation state", self::EXCEPTION_CODE_SQUAD_INVALID_STATE);
        }

        $heroName = NPC::heroName($squad);
        $heroRank = HeroRank::getStarting();
        return $this->createHeroAction->execute($heroName, $squad, $heroClass, $heroRace, $heroRank);
    }
}

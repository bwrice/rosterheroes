<?php


namespace App\Domain\Actions\NPC;


use App\Domain\Actions\CreateSquadAction;
use App\Domain\Models\HeroClass;
use App\Domain\Models\HeroPost;
use App\Domain\Models\HeroRace;
use App\Domain\Models\Squad;
use App\Facades\NPC;

class CreateNPCSquad
{
    /**
     * @var CreateSquadAction
     */
    protected $createSquadAction;
    /**
     * @var CreateNPCHero
     */
    protected $createNPCHero;

    public function __construct(CreateSquadAction $createSquadAction, CreateNPCHero $createNPCHero)
    {
        $this->createSquadAction = $createSquadAction;
        $this->createNPCHero = $createNPCHero;
    }

    /**
     * @return Squad
     */
    public function execute()
    {
        $user = NPC::user();
        $squadName = NPC::squadName();
        $squad = $this->createSquadAction->execute($user->id, $squadName);
        $startingClasses = HeroClass::requiredStarting()->get();

        $squad->heroPosts()->with('heroPostType.heroRaces')->get()->each(function (HeroPost $heroPost) use ($squad, $startingClasses) {
            /** @var HeroRace $race */
            $race = $heroPost->getHeroRaces()->random();
            /** @var HeroClass $class */
            $class = $startingClasses->shift() ?: HeroClass::requiredStarting()->inRandomOrder()->first();
            $this->createNPCHero->execute($squad, $race, $class);
        });

        return $squad->fresh();
    }
}

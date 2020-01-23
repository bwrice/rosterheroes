<?php


namespace App\Domain\Actions\Testing;


use App\Domain\Actions\AddNewHeroToSquadAction;
use App\Domain\Models\HeroClass;
use App\Domain\Models\HeroRace;
use App\Domain\Models\Squad;

class AddTestHeroToTestSquadAction
{
    /**
     * @var AddNewHeroToSquadAction
     */
    private $addNewHeroToSquadAction;

    public function __construct(AddNewHeroToSquadAction $addNewHeroToSquadAction)
    {
        $this->addNewHeroToSquadAction = $addNewHeroToSquadAction;
    }

    /**
     * @param Squad $testSquad
     * @param HeroRace $heroRace
     * @param int $testID
     * @return \App\Domain\Models\Hero
     * @throws \App\Exceptions\HeroPostNotFoundException
     * @throws \App\Exceptions\InvalidHeroClassException
     */
    public function execute(Squad $testSquad, HeroRace $heroRace, int $testID)
    {
        $name = $this->buildName($heroRace, $testID);
        $heroClass = $this->getHeroClass($heroRace);
        $hero = $this->addNewHeroToSquadAction->execute($testSquad, $name, $heroClass, $heroRace);
        return $hero;
    }

    protected function buildName(HeroRace $heroRace, int $testID)
    {
        return 'Test' . ucfirst($heroRace->name) . $testID;
    }

    /**
     * @param HeroRace $heroRace
     * @return HeroClass
     */
    protected function getHeroClass(HeroRace $heroRace)
    {
        switch ($heroRace->name) {
            case HeroRace::HUMAN:
                return HeroClass::ranger();
            case HeroRace::ELF:
                return HeroClass::sorcerer();
            case HeroRace::DWARF:
            case HeroRace::ORC:
                return HeroClass::warrior();

        }
        throw new \InvalidArgumentException("Invalid hero race: " . $heroRace->name);
    }
}

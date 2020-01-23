<?php


namespace App\Domain\Actions;


use App\Domain\Collections\SquadCollection;
use App\Domain\Models\HeroClass;
use App\Domain\Models\HeroRace;
use App\Domain\Models\Squad;

class CreateActiveTestSquadAction
{
    /**
     * @var CreateUserAction
     */
    private $createUserAction;
    /**
     * @var CreateSquadAction
     */
    private $createSquadAction;
    /**
     * @var AddNewHeroToSquadAction
     */
    private $addNewHeroToSquadAction;

    public function __construct(
        CreateUserAction $createUserAction,
        CreateSquadAction $createSquadAction,
        AddNewHeroToSquadAction $addNewHeroToSquadAction)
    {
        $this->createUserAction = $createUserAction;
        $this->createSquadAction = $createSquadAction;
        $this->addNewHeroToSquadAction = $addNewHeroToSquadAction;
    }

    public function execute($amount = 1): SquadCollection
    {
        $squads = new SquadCollection();
        $offset = Squad::query()->count();
        foreach (range(1, $amount) as $count) {
            $testID = $offset + $count;
            $user = $this->createUserAction->execute('testUser' . $testID . '@test.com', 'TestUser' . $testID, 'password');
            $squad = $this->createSquadAction->execute($user->id, 'ActiveTestSquad' . $testID);

            $heroes = [
                [
                    'name' => 'TestHuman' . $testID,
                    'hero_race' => HeroRace::human(),
                    'hero_class' => HeroClass::ranger()
                ],
                [
                    'name' => 'TestElf' . $testID,
                    'hero_race' => HeroRace::elf(),
                    'hero_class' => HeroClass::sorcerer()
                ],
                [
                    'name' => 'TestDwarf' . $testID,
                    'hero_race' => HeroRace::dwarf(),
                    'hero_class' => HeroClass::warrior()
                ],
                [
                    'name' => 'TestOrc' . $testID,
                    'hero_race' => HeroRace::orc(),
                    'hero_class' => HeroClass::warrior()
                ]
            ];

            foreach ($heroes as $hero) {
                $this->addNewHeroToSquadAction->execute($squad, $hero['name'], $hero['hero_class'], $hero['hero_race']);
            }
            $squads->push($squad);
        }
        return $squads;
    }
}

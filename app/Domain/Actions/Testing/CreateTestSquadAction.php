<?php


namespace App\Domain\Actions\Testing;


use App\Domain\Actions\AddNewHeroToSquadAction;
use App\Domain\Actions\CreateSquadAction;
use App\Domain\Actions\CreateUserAction;
use App\Domain\Collections\SquadCollection;
use App\Domain\Models\HeroClass;
use App\Domain\Models\HeroRace;
use App\Domain\Models\Squad;
use App\Jobs\AddTestHeroToTestSquadJob;

class CreateTestSquadAction
{
    /**
     * @var CreateUserAction
     */
    protected $createUserAction;
    /**
     * @var CreateSquadAction
     */
    protected $createSquadAction;

    public function __construct(
        CreateUserAction $createUserAction,
        CreateSquadAction $createSquadAction)
    {
        $this->createUserAction = $createUserAction;
        $this->createSquadAction = $createSquadAction;
    }

    public function execute($amount = 1): SquadCollection
    {
        $squads = new SquadCollection();
        $offset = Squad::query()->count();
        foreach (range(1, $amount) as $count) {
            $testID = $offset + $count;
            $user = $this->createUserAction->execute('testUser' . $testID . '@test.com', 'TestUser' . $testID, 'password');
            $squad = $this->createSquadAction->execute($user->id, 'TestSquad' . $testID);

            HeroRace::starting()->get()->each(function (HeroRace $heroRace) use ($squad, $testID) {
                AddTestHeroToTestSquadJob::dispatch($squad, $heroRace, $testID);
            });

            $squads->push($squad);
        }
        return $squads;
    }
}

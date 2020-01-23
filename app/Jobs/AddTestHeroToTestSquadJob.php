<?php

namespace App\Jobs;

use App\Domain\Actions\Testing\AddTestHeroToTestSquadAction;
use App\Domain\Models\HeroRace;
use App\Domain\Models\Squad;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AddTestHeroToTestSquadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var Squad
     */
    public $squad;
    /**
     * @var HeroRace
     */
    public $heroRace;
    /**
     * @var int
     */
    public $testID;

    /**
     * AddTestHeroToTestSquadJob constructor.
     * @param Squad $squad
     * @param HeroRace $heroRace
     * @param int $testID
     */
    public function __construct(Squad $squad, HeroRace $heroRace, int $testID)
    {
        $this->squad = $squad;
        $this->heroRace = $heroRace;
        $this->testID = $testID;
    }

    /**
     * @param AddTestHeroToTestSquadAction $domainAction
     * @throws \App\Exceptions\HeroPostNotFoundException
     * @throws \App\Exceptions\InvalidHeroClassException
     */
    public function handle(AddTestHeroToTestSquadAction $domainAction)
    {
        $domainAction->execute($this->squad, $this->heroRace, $this->testID);
    }
}

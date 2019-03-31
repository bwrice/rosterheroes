<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 2/3/19
 * Time: 6:46 PM
 */

namespace App\Squads;


use App\Domain\Models\HeroPost;
use App\Domain\Collections\HeroPostCollection;
use App\Domain\Models\HeroRace;
use App\Domain\Models\Squad;
use Illuminate\Support\Collection;

class HeroRaceAvailability
{
    /**
     * @var HeroPostAvailability
     */
    private $heroPostAvailability;

    public function __construct(HeroPostAvailability $heroPostAvailability)
    {
        $this->heroPostAvailability = $heroPostAvailability;
    }

    /**
     * @param \App\Domain\Models\Squad $squad
     * @return Collection|\App\Domain\Models\HeroRace[]
     */
    public function get(Squad $squad)
    {
        $availableHeroPosts = $this->heroPostAvailability->get($squad);
        $heroRaces = collect();
        $availableHeroPosts->each(function(HeroPost $heroPost) use ($heroRaces) {
            return $heroRaces->push($heroPost->heroRace);
        });

        return $heroRaces->unique();
    }
}
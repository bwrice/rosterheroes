<?php

namespace App\Jobs;

use App\Domain\Actions\RecruitHero;
use App\Domain\Models\HeroClass;
use App\Domain\Models\HeroPostType;
use App\Domain\Models\HeroRace;
use App\Domain\Models\RecruitmentCamp;
use App\Domain\Models\Squad;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RecruitHeroForNPCJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Squad $npc;
    public RecruitmentCamp $recruitmentCamp;
    public HeroPostType $heroPostType;
    public HeroRace $heroRace;
    public HeroClass $heroClass;
    public string $heroName;

    /**
     * RecruitHeroForNPCJob constructor.
     * @param Squad $npc
     * @param RecruitmentCamp $recruitmentCamp
     * @param HeroPostType $heroPostType
     * @param HeroRace $heroRace
     * @param HeroClass $heroClass
     * @param string $heroName
     */
    public function __construct(
        Squad $npc,
        RecruitmentCamp $recruitmentCamp,
        HeroPostType $heroPostType,
        HeroRace $heroRace,
        HeroClass $heroClass,
        string $heroName)
    {

        $this->npc = $npc;
        $this->recruitmentCamp = $recruitmentCamp;
        $this->heroPostType = $heroPostType;
        $this->heroRace = $heroRace;
        $this->heroClass = $heroClass;
        $this->heroName = $heroName;
    }

    /**
     * @param RecruitHero $recruitHero
     * @throws \App\Exceptions\HeroPostNotFoundException
     * @throws \App\Exceptions\InvalidHeroClassException
     * @throws \App\Exceptions\RecruitHeroException
     */
    public function handle(RecruitHero $recruitHero)
    {
        $recruitHero->execute($this->npc, $this->recruitmentCamp, $this->heroPostType, $this->heroRace, $this->heroClass, $this->heroName);
    }
}

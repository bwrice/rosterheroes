<?php

namespace App\Jobs;

use App\Domain\Actions\ProvinceEvents\CreateSquadRecruitsHeroEvent;
use App\Domain\Models\Hero;
use App\Domain\Models\Province;
use App\Domain\Models\RecruitmentCamp;
use App\Domain\Models\Squad;
use Carbon\CarbonInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateSquadRecruitsHeroEventJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Squad $squad;
    public Hero $hero;
    public RecruitmentCamp $recruitmentCamp;
    public Province $province;
    public CarbonInterface $happenedAt;

    /**
     * CreateSquadRecruitsHeroEventJob constructor.
     * @param Squad $squad
     * @param Hero $hero
     * @param RecruitmentCamp $recruitmentCamp
     * @param Province $province
     * @param CarbonInterface $happenedAt
     */
    public function __construct(
        Squad $squad,
        Hero $hero,
        RecruitmentCamp $recruitmentCamp,
        Province $province,
        CarbonInterface $happenedAt
    )
    {
        $this->squad = $squad;
        $this->hero = $hero;
        $this->recruitmentCamp = $recruitmentCamp;
        $this->province = $province;
        $this->happenedAt = $happenedAt;
    }

    /**
     * @param CreateSquadRecruitsHeroEvent $createSquadRecruitsHeroEvent
     */
    public function handle(CreateSquadRecruitsHeroEvent $createSquadRecruitsHeroEvent)
    {
        $createSquadRecruitsHeroEvent->execute($this->squad, $this->hero, $this->recruitmentCamp, $this->province, $this->happenedAt);
    }
}

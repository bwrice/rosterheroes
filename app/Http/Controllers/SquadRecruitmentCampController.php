<?php

namespace App\Http\Controllers;

use App\Domain\Models\HeroPostType;
use App\Domain\Models\RecruitmentCamp;
use App\Domain\Models\Squad;
use App\Http\Resources\RecruitmentCampResource;
use App\Policies\SquadPolicy;
use Illuminate\Http\Request;

class SquadRecruitmentCampController extends Controller
{

    public function show($squadSlug, $recruitmentCampSlug)
    {
        $squad = Squad::findSlugOrFail($squadSlug);
        $recruitmentCamp = RecruitmentCamp::findSlugOrFail($recruitmentCampSlug);

        $this->authorize(SquadPolicy::VISIT_MERCHANT, [
            $squad,
            $recruitmentCamp
        ]);

        $recruitmentCamp->load(['heroPostTypes.heroRaces', 'heroClasses']);

        $recruitmentCamp->heroPostTypes->each(function (HeroPostType $heroPostType) use ($squad) {
            $heroPostType->setRecruitmentCost($squad);
            $heroPostType->setRecruitmentBonusSpiritEssence($squad);
        });

        return new RecruitmentCampResource($recruitmentCamp);
    }
}

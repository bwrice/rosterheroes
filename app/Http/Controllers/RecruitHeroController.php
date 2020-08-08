<?php

namespace App\Http\Controllers;

use App\Domain\Actions\RecruitHero;
use App\Domain\Models\HeroClass;
use App\Domain\Models\HeroPostType;
use App\Domain\Models\HeroRace;
use App\Domain\Models\RecruitmentCamp;
use App\Domain\Models\Squad;
use App\Exceptions\RecruitHeroException;
use App\Policies\SquadPolicy;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class RecruitHeroController extends Controller
{
    /**
     * @param $squadSlug
     * @param $recruitmentCampSlug
     * @param Request $request
     * @param RecruitHero $domainAction
     * @throws ValidationException
     * @throws \App\Exceptions\HeroPostNotFoundException
     * @throws \App\Exceptions\InvalidHeroClassException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke($squadSlug, $recruitmentCampSlug, Request $request, RecruitHero $domainAction)
    {
        $squad = Squad::findSlugOrFail($squadSlug);
        $this->authorize(SquadPolicy::MANAGE, $squad);

        $recruitmentCamp = RecruitmentCamp::findSlugOrFail($recruitmentCampSlug);

        $this->validate($request, [
            'heroName' => 'required|regex:/^[\w\-\s]+$/|between:4,16|unique:heroes,name',
        ]);

        /** @var HeroPostType $heroPostType */
        $heroPostType = HeroPostType::query()->findOrFail($request->heroPostType);
        /** @var HeroRace $heroRace */
        $heroRace = HeroRace::query()->findOrFail($request->heroRace);
        /** @var HeroClass $heroClass */
        $heroClass = HeroClass::query()->findOrFail($request->heroClass);

        try {

            $domainAction->execute($squad, $recruitmentCamp, $heroPostType, $heroRace, $heroClass, $request->heroName);

        } catch (RecruitHeroException $recruitHeroException) {

            throw ValidationException::withMessages([
                'recruit' => $recruitHeroException->getMessage()
            ]);
        }
    }
}

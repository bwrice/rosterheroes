<?php


namespace App\Domain\Actions\NPC;


use App\Domain\Models\Continent;
use App\Domain\Models\RecruitmentCamp;
use App\Domain\Models\Shop;
use App\Domain\Models\Squad;
use App\Facades\HeroPostTypeFacade;
use Illuminate\Database\Eloquent\Builder;

class FindRecruitmentCamp
{
    /**
     * @param Squad $npc
     * @return RecruitmentCamp|null
     */
    public function execute(Squad $npc)
    {
        $validContinentIDs = Continent::query()->get()->filter(function (Continent $continent) use ($npc) {
            return $continent->getBehavior()->getMinLevelRequirement() <= $npc->level();
        })->pluck('id')->toArray();

        $cheapestHeroPostTypeIDs = HeroPostTypeFacade::cheapestForSquad($npc)->pluck('id')->toArray();

        /** @var RecruitmentCamp|null $recruitmentCamp */
        $recruitmentCamp = RecruitmentCamp::query()
            ->whereHas('province', function (Builder $builder) use ($validContinentIDs) {
                return $builder->whereIn('continent_id', $validContinentIDs);
            })->whereHas('heroPostTypes', function (Builder $builder) use ($cheapestHeroPostTypeIDs) {
                return $builder->whereIn('id', $cheapestHeroPostTypeIDs);
            })->inRandomOrder()
            ->first();
        return $recruitmentCamp;
    }
}

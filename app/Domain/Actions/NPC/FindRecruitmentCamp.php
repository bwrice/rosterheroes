<?php


namespace App\Domain\Actions\NPC;


use App\Domain\Models\Continent;
use App\Domain\Models\HeroPostType;
use App\Domain\Models\RecruitmentCamp;
use App\Domain\Models\Squad;
use Illuminate\Database\Eloquent\Builder;

class FindRecruitmentCamp
{
    /**
     * @param Squad $npc
     * @param HeroPostType $heroPostType
     * @return RecruitmentCamp|null
     */
    public function execute(Squad $npc, HeroPostType $heroPostType)
    {
        $validContinentIDs = Continent::query()->get()->filter(function (Continent $continent) use ($npc) {
            return $continent->getBehavior()->getMinLevelRequirement() <= $npc->level();
        })->pluck('id')->toArray();

        /** @var RecruitmentCamp|null $recruitmentCamp */
        $recruitmentCamp = RecruitmentCamp::query()
            ->whereHas('province', function (Builder $builder) use ($validContinentIDs) {
                return $builder->whereIn('continent_id', $validContinentIDs);
            })->whereHas('heroPostTypes', function (Builder $builder) use ($heroPostType) {
                return $builder->where('id', '=', $heroPostType->id);
            })->inRandomOrder()
            ->first();
        return $recruitmentCamp;
    }
}

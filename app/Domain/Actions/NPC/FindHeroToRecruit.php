<?php


namespace App\Domain\Actions\NPC;


use App\Domain\Models\Hero;
use App\Domain\Models\HeroPostType;
use App\Domain\Models\Squad;
use App\Facades\HeroPostTypeFacade;
use App\Facades\NPC;
use Illuminate\Support\Collection;

class FindHeroToRecruit
{
    const NPC_EXTRA_GOLD = 5000;

    protected FindRecruitmentCamp $findRecruitmentCamp;

    public function __construct(FindRecruitmentCamp $findRecruitmentCamp)
    {
        $this->findRecruitmentCamp = $findRecruitmentCamp;
    }

    public function execute(Squad $npc)
    {
        /** @var HeroPostType $heroPostType */
        $heroPostType = HeroPostTypeFacade::cheapestForSquad($npc)->random();
        $recruitmentCost = $heroPostType->getRecruitmentCost($npc);

        if ($recruitmentCost > ($npc->gold - self::NPC_EXTRA_GOLD)) {
            return null;
        }

        $heroRace = $heroPostType->heroRaces()->inRandomOrder()->first();
        $recruitmentCamp = $this->findRecruitmentCamp->execute($npc, $heroPostType);


        return [
            'hero_post_type' => $heroPostType,
            'recruitment_camp' => $recruitmentCamp,
            'hero_race' => $heroRace,
            'hero_class' => $this->getLeastUsedHeroClass($npc),
            'name' => NPC::heroName($npc)
        ];
    }

    protected function getLeastUsedHeroClass(Squad $npc)
    {
        /** @var Collection $heroesOfLeastUsedClass */
        $heroesOfLeastUsedClass = $npc->heroes
            ->load('heroClass')
            ->shuffle()
            ->groupBy(function (Hero $hero) {
                return $hero->heroClass->name;
            })->sortBy(function (Collection $heroesOfClass) {
                return $heroesOfClass->count();
            })->first();

        /** @var Hero $hero */
        // We'll just grab the first hero since they'll all have the same hero-class
        $hero = $heroesOfLeastUsedClass->first();
        return $hero->heroClass;
    }
}

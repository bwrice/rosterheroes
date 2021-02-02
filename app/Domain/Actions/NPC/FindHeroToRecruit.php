<?php


namespace App\Domain\Actions\NPC;


use App\Domain\Models\HeroPostType;
use App\Domain\Models\Squad;
use App\Facades\HeroPostTypeFacade;

class FindHeroToRecruit
{
    const NPC_EXTRA_GOLD = 25000;

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
            'hero_race' => $heroRace
        ];
    }
}

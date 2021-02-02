<?php

namespace Tests\Feature;

use App\Domain\Actions\NPC\FindHeroToRecruit;
use App\Domain\Models\HeroPostType;
use App\Facades\HeroPostTypeFacade;
use App\Factories\Models\SquadFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FindHeroToRecruitTest extends TestCase
{

    use DatabaseTransactions;

    /**
     * @return FindHeroToRecruit
     */
    protected function getDomainAction()
    {
        return app(FindHeroToRecruit::class);
    }

    /**
     * @test
     */
    public function it_will_return_null_if_an_npc_does_not_have_enough_gold()
    {
        /** @var HeroPostType $heroPostType */
        $heroPostType = HeroPostType::query()->inRandomOrder()->first();
        $recruitmentCost = $heroPostType->getBehavior()->getRecruitmentCost(0);
        $npc = SquadFactory::new()
            ->withGold($recruitmentCost + FindHeroToRecruit::NPC_EXTRA_GOLD - 1)
            ->withStartingHeroes()
            ->create();

        HeroPostTypeFacade::shouldReceive('cheapestForSquad')->andReturn(collect([$heroPostType]));
        $returnValue = $this->getDomainAction()->execute($npc);
        $this->assertNull($returnValue);
    }
}

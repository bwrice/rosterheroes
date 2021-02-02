<?php

namespace Tests\Feature;

use App\Domain\Actions\NPC\FindHeroToRecruit;
use App\Domain\Models\HeroPostType;
use App\Domain\Models\Squad;
use App\Facades\HeroPostTypeFacade;
use App\Factories\Models\SquadFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FindHeroToRecruitTest extends TestCase
{

    use DatabaseTransactions;

    /** @var HeroPostType */
    protected $heroPostType;
    /** @var Squad */
    protected $npc;

    public function setUp(): void
    {
        parent::setUp();
        $this->heroPostType = HeroPostType::query()->inRandomOrder()->first();
        $this->npc = SquadFactory::new()->withStartingHeroes()->create();
        HeroPostTypeFacade::shouldReceive('cheapestForSquad')->andReturn(collect([$this->heroPostType]));
    }

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
        $recruitmentCost = $this->heroPostType->getBehavior()->getRecruitmentCost(0);
        $this->npc->gold = $recruitmentCost + FindHeroToRecruit::NPC_EXTRA_GOLD - 1;
        $this->npc->save();

        $returnValue = $this->getDomainAction()->execute($this->npc);
        $this->assertNull($returnValue);
    }
}

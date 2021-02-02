<?php

namespace Tests\Feature;

use App\Domain\Actions\NPC\FindHeroToRecruit;
use App\Domain\Actions\NPC\FindRecruitmentCamp;
use App\Domain\Models\HeroPostType;
use App\Domain\Models\RecruitmentCamp;
use App\Domain\Models\Squad;
use App\Facades\HeroPostTypeFacade;
use App\Factories\Models\RecruitmentCampFactory;
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
    /** @var int */
    protected $recruitmentCost;
    /** @var RecruitmentCamp */
    protected $recruitmentCamp;

    public function setUp(): void
    {
        parent::setUp();
        $this->heroPostType = HeroPostType::query()->inRandomOrder()->first();
        $this->recruitmentCost = $this->heroPostType->getBehavior()->getRecruitmentCost();
        $this->npc = SquadFactory::new()
            ->withGold($this->recruitmentCost + FindHeroToRecruit::NPC_EXTRA_GOLD + 1000)
            ->withStartingHeroes()
            ->create();
        HeroPostTypeFacade::shouldReceive('cheapestForSquad')->andReturn(collect([$this->heroPostType]));

        $this->recruitmentCamp = RecruitmentCampFactory::new()->create();
        $findRecruitmentCampMock = \Mockery::mock(FindRecruitmentCamp::class)
            ->shouldReceive('execute')
            ->andReturn($this->recruitmentCamp)
            ->getMock();
        $this->instance(FindRecruitmentCamp::class, $findRecruitmentCampMock);
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
    public function it_will_return_null_if_an_npc_does_not_have_enough_extra_gold()
    {
        $this->npc->gold = $this->recruitmentCost + FindHeroToRecruit::NPC_EXTRA_GOLD - 1;
        $this->npc->save();

        $returnValue = $this->getDomainAction()->execute($this->npc);
        $this->assertNull($returnValue);
    }

    /**
     * @test
     */
    public function it_will_return_a_hero_post_type_when_finding_a_hero_to_recruit()
    {
        $returnValue = $this->getDomainAction()->execute($this->npc);
        $this->assertEquals($returnValue['hero_post_type']->id, $this->heroPostType->id);
    }

    /**
     * @test
     */
    public function it_will_return_a_recruitment_camp_when_finding_a_hero_to_recruit()
    {
        $returnValue = $this->getDomainAction()->execute($this->npc);
        $this->assertEquals($returnValue['recruitment_camp']->id, $this->recruitmentCamp->id);
    }
}

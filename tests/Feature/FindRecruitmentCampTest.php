<?php

namespace Tests\Feature;

use App\Domain\Actions\NPC\FindRecruitmentCamp;
use App\Domain\Models\Continent;
use App\Domain\Models\HeroPostType;
use App\Domain\Models\Province;
use App\Facades\HeroPostTypeFacade;
use App\Factories\Models\RecruitmentCampFactory;
use App\Factories\Models\SquadFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FindRecruitmentCampTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return FindRecruitmentCamp
     */
    protected function getDomainAction()
    {
        return app(FindRecruitmentCamp::class);
    }

    /**
     * @test
     */
    public function it_wont_return_recruitment_camp_from_invalid_continent()
    {
        $heroPostTypes = HeroPostType::all()->shuffle();
        $campPostTypes = collect();

        $invalidContinent = Continent::query()->where('name', '=', Continent::DEMAUXOR)->first();
        $invalidProvinces = Province::query()
            ->where('continent_id', '=', $invalidContinent->id)
            ->inRandomOrder()
            ->take(20)
            ->get();

        /*
         * We'll pull some random hero-post types for the camps to have
         * and to mock the return from the HeroPostTypeFacade
         */
        for ($i = 1; $i <= rand(2, 3); $i++) {
            $campPostTypes->push($heroPostTypes->shift());
        }

        $recruitmentCamp = RecruitmentCampFactory::new()
            ->withHeroPostTypes($campPostTypes);
        $invalidCamps = collect();
        for ($i = 1; $i <= 10; $i++) {
            $invalidCamps->push($recruitmentCamp->withProvinceID($invalidProvinces->random()->id)->create());
        }

        HeroPostTypeFacade::shouldReceive('cheapestForSquad')->andReturn($campPostTypes);

        $npc = SquadFactory::new()->atProvince(Province::getStarting()->id)->create();

        $recruitmentCamp = $this->getDomainAction()->execute($npc);
        if ($recruitmentCamp) {
            $this->assertFalse(in_array($recruitmentCamp->id, $invalidCamps->pluck('id')->toArray()));
        } else {
            $this->assertNull($recruitmentCamp);
        }
    }

    /**
     * @test
     */
    public function it_wont_return_recruitment_camp_without_valid_hero_post_types()
    {
        $npc = SquadFactory::new()->atProvince(Province::getStarting()->id)->create();
        $validProvinces = Province::query()
            ->where('continent_id', '=', $npc->province->id)
            ->inRandomOrder()
            ->take(20)
            ->get();

        $cheatPostTypes = HeroPostType::all()->shuffle();
        $campPostTypes = collect();

        /*
         * We'll pull some random hero-post types for the camps to have
         * and to mock the rest as the return from the HeroPostTypeFacade
         */
        for ($i = 1; $i <= rand(2, 3); $i++) {
            $campPostTypes->push($cheatPostTypes->shift());
        }

        $recruitmentCamp = RecruitmentCampFactory::new()
            ->withHeroPostTypes($campPostTypes);
        $invalidCamps = collect();
        for ($i = 1; $i <= 10; $i++) {
            $invalidCamps->push($recruitmentCamp->withProvinceID($validProvinces->random()->id)->create());
        }

        HeroPostTypeFacade::shouldReceive('cheapestForSquad')->andReturn($campPostTypes);

        $npc = SquadFactory::new()->atProvince(Province::getStarting()->id)->create();

        $recruitmentCamp = $this->getDomainAction()->execute($npc);
        if ($recruitmentCamp) {
            $this->assertFalse(in_array($recruitmentCamp->id, $invalidCamps->pluck('id')->toArray()));
        } else {
            $this->assertNull($recruitmentCamp);
        }
    }
}

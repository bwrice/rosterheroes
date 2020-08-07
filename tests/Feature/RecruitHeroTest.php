<?php

namespace Tests\Feature;

use App\Domain\Actions\RecruitHero;
use App\Domain\Models\HeroClass;
use App\Domain\Models\HeroPost;
use App\Domain\Models\HeroPostType;
use App\Domain\Models\HeroRace;
use App\Domain\Models\RecruitmentCamp;
use App\Domain\Models\Squad;
use App\Exceptions\RecruitHeroException;
use App\Facades\CurrentWeek;
use App\Factories\Models\HeroFactory;
use App\Factories\Models\RecruitmentCampFactory;
use App\Factories\Models\SquadFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RecruitHeroTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Squad */
    protected $squad;

    /** @var RecruitmentCamp */
    protected $recruitmentCamp;

    /** @var HeroPostType */
    protected $heroPostType;

    /** @var HeroClass */
    protected $heroClass;

    /** @var HeroRace */
    protected $heroRace;

    public function setUp(): void
    {
        parent::setUp();
        $this->squad = SquadFactory::new()->withStartingHeroes()->create();
        $this->recruitmentCamp = RecruitmentCampFactory::new()->withProvinceID($this->squad->province_id)->create();

        $starting = collect(HeroPostType::squadStarting())->map(function ($startingTypes) {
            return $startingTypes['name'];
        });
        $this->heroPostType = HeroPostType::query()->whereIn('name', $starting->toArray())->inRandomOrder()->first();
        $this->heroRace = $this->heroPostType->heroRaces()->inRandomOrder()->first();
        $this->heroClass = HeroClass::query()->inRandomOrder()->first();

        $cost = $this->heroPostType->getRecruitmentCost($this->squad);
        $this->squad->gold = $cost;
        $this->squad->save();
    }

    /**
     * @return RecruitHero
     */
    protected function getDomainAction()
    {
        return app(RecruitHero::class);
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_current_week_is_locked()
    {
        CurrentWeek::shouldReceive('adventuringLocked')->andReturn(true);
        try {
            $this->getDomainAction()->execute($this->squad, $this->recruitmentCamp, $this->heroPostType, $this->heroRace, $this->heroClass);
        } catch (RecruitHeroException $exception) {
            $this->assertEquals($exception->getCode(), RecruitHeroException::CODE_WEEK_LOCKED);
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_squad_is_not_at_the_same_province_as_the_camp()
    {
        CurrentWeek::shouldReceive('adventuringLocked')->andReturn(false);
        $recruitmentCampProvinceID = $this->recruitmentCamp->province_id;
        $this->squad->province_id = $recruitmentCampProvinceID == 1 ? 2 : $recruitmentCampProvinceID - 1;
        $this->squad->save();

        try {
            $this->getDomainAction()->execute($this->squad, $this->recruitmentCamp, $this->heroPostType, $this->heroRace, $this->heroClass);
        } catch (RecruitHeroException $exception) {
            $this->assertEquals($exception->getCode(), RecruitHeroException::CODE_INVALID_SQUAD_LOCATION);
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_hero_race_does_not_belong_to_the_hero_post_type()
    {
        CurrentWeek::shouldReceive('adventuringLocked')->andReturn(false);
        $invalidHeroRace = HeroRace::query()
            ->whereNotIn('id', $this->heroPostType->heroRaces->pluck('id')->toArray())
            ->inRandomOrder()
            ->first();

        try {
            $this->getDomainAction()->execute($this->squad, $this->recruitmentCamp, $this->heroPostType, $invalidHeroRace, $this->heroClass);
        } catch (RecruitHeroException $exception) {
            $this->assertEquals($exception->getCode(), RecruitHeroException::CODE_INVALID_HERO_RACE);
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_squad_does_not_have_enough_gold_to_recruit_hero()
    {
        $goldCost = $this->heroPostType->getRecruitmentCost($this->squad);
        $this->squad->gold = $goldCost - 1;
        $this->squad->save();

        try {
            $this->getDomainAction()->execute($this->squad, $this->recruitmentCamp, $this->heroPostType, $this->heroRace, $this->heroClass);
        } catch (RecruitHeroException $exception) {
            $this->assertEquals($exception->getCode(), RecruitHeroException::CODE_NOT_ENOUGH_GOLD);
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_create_a_hero_post_for_the_squad_of_the_same_post_type_recruited()
    {
        $initialPostTypesCount = $this->squad->heroPosts->filter(function (HeroPost $heroPost) {
            return $heroPost->hero_post_type_id === $this->heroPostType->id;
        })->count();


        $this->getDomainAction()->execute($this->squad, $this->recruitmentCamp, $this->heroPostType, $this->heroRace, $this->heroClass);

        $squad = $this->squad->fresh();

        $currentCount = $squad->heroPosts->filter(function (HeroPost $heroPost) {
            return $heroPost->hero_post_type_id === $this->heroPostType->id;
        })->count();

        $this->assertEquals($initialPostTypesCount + 1, $currentCount);
    }
}

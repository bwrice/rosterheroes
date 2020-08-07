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
use Illuminate\Support\Str;
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

    /** @var string */
    protected $heroName;

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

        $this->heroName = (string) Str::random();
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
            $this->getDomainAction()->execute($this->squad, $this->recruitmentCamp, $this->heroPostType, $this->heroRace, $this->heroClass, $this->heroName);
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
            $this->getDomainAction()->execute($this->squad, $this->recruitmentCamp, $this->heroPostType, $this->heroRace, $this->heroClass, $this->heroName);
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

        /** @var HeroRace $invalidHeroRace */
        $invalidHeroRace = HeroRace::query()
            ->whereNotIn('id', $this->heroPostType->heroRaces->pluck('id')->toArray())
            ->inRandomOrder()
            ->first();

        try {
            $this->getDomainAction()->execute($this->squad, $this->recruitmentCamp, $this->heroPostType, $invalidHeroRace, $this->heroClass, $this->heroName);
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
            $this->getDomainAction()->execute($this->squad, $this->recruitmentCamp, $this->heroPostType, $this->heroRace, $this->heroClass, $this->heroName);
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


        $this->getDomainAction()->execute($this->squad, $this->recruitmentCamp, $this->heroPostType, $this->heroRace, $this->heroClass, $this->heroName);

        $squad = $this->squad->fresh();

        $currentCount = $squad->heroPosts->filter(function (HeroPost $heroPost) {
            return $heroPost->hero_post_type_id === $this->heroPostType->id;
        })->count();

        $this->assertEquals($initialPostTypesCount + 1, $currentCount);
    }

    /**
     * @test
     */
    public function it_will_create_a_new_hero_for_the_squad_recruiting()
    {
        $initialHeroesCount = $this->squad->heroes()->count();

        $heroCreated = $this->getDomainAction()->execute($this->squad, $this->recruitmentCamp, $this->heroPostType, $this->heroRace, $this->heroClass, $this->heroName);

        $this->assertEquals($initialHeroesCount + 1, $this->squad->heroes()->count());

        $this->assertEquals($this->heroName, $heroCreated->name);
        $this->assertEquals($this->heroRace->id, $heroCreated->hero_race_id);
        $this->assertEquals($this->heroClass->id, $heroCreated->hero_class_id);
        $this->assertEquals($this->squad->id, $heroCreated->squad_id);
    }
}

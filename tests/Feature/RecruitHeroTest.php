<?php

namespace Tests\Feature;

use App\Domain\Models\HeroClass;
use App\Domain\Models\HeroPostType;
use App\Domain\Models\HeroRace;
use App\Domain\Models\RecruitmentCamp;
use App\Domain\Models\Squad;
use App\Factories\Models\RecruitmentCampFactory;
use App\Factories\Models\SquadFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

abstract class RecruitHeroTest extends TestCase
{
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

    /** @var int */
    protected $initialGold;

    /** @var int */
    protected $initialSpiritEssence;

    /** @var int */
    protected $initialHeroPostsCount;

    /** @var int */
    protected $initialHeroesCount;

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
        $this->squad->gold = $this->initialGold = $cost + rand(0, 100);
        $this->squad->save();

        $this->initialSpiritEssence = $this->squad->spirit_essence;
        $this->initialHeroesCount = $this->squad->heroes()->count();
        $this->initialHeroPostsCount = $this->squad->heroPosts()->count();

        $this->heroName = (string) Str::random();
    }
}

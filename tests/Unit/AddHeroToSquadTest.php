<?php

namespace Tests\Unit;

use App\Domain\Actions\AddHeroToSquad;
use App\Exceptions\HeroPostNotFoundException;
use App\Exceptions\InvalidHeroClassException;
use App\Domain\Models\Hero;
use App\Domain\Models\HeroClass;
use App\Domain\Models\HeroPost;
use App\Domain\Models\HeroRace;
use App\Domain\Models\Squad;
use App\HeroPostType;
use App\Squads\HeroClassAvailability;
use App\Squads\HeroPostAvailability;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddHeroToSquadTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function adding_a_hero_without_a_hero_post_will_throw_an_exception()
    {
        /** @var \App\Domain\Models\Squad $squad */
        $squad = factory(Squad::class)->create();

        $this->assertEquals(0, $squad->heroPosts->count());
        /** @var HeroRace $heroRace */
        $heroRace = HeroRace::query()->inRandomOrder()->first();
        /** @var \App\Domain\Models\HeroClass $heroClass */
        $heroClass = HeroClass::query()->inRandomOrder()->first();

        try {
            $action = new AddHeroToSquad($squad, $heroRace, $heroClass, 'TestHero' . rand(1,999999));
            $action();
        } catch (HeroPostNotFoundException $exception) {
            $this->assertEquals(0, $squad->getHeroes()->count());
            return;
        }

        $this->fail("Exception Not Thrown");
    }

    /**
     * @test
     */
    public function adding_a_hero_without_a_matching_hero_post_will_throw_an_exception()
    {
        /** @var \App\Domain\Models\HeroPost $heroPost */
        $heroPost = factory(HeroPost::class)->create();
        /** @var \App\Domain\Models\HeroRace $heroRace */
        $validHeroRaceIDs = $heroPost->heroPostType->heroRaces->pluck('id')->toArray();
        // get Hero Race with ID NOT equal to a valid hero race ID
        $heroRace = HeroRace::query()->whereNotIn('id', $validHeroRaceIDs)->inRandomOrder()->first();
        /** @var \App\Domain\Models\HeroClass $heroClass */
        $heroClass = HeroClass::query()->inRandomOrder()->first();

        try {
            $action = new AddHeroToSquad($heroPost->squad, $heroRace, $heroClass, 'TestHero' . rand(1,999999));
            $action();
        } catch (HeroPostNotFoundException $exception) {
            $this->assertEquals(0, $heroPost->squad->getHeroes()->count());
            return;
        }

        $this->fail("Exception Not Thrown");
    }

    /**
     * @test
     */
    public function adding_a_hero_of_a_non_needed_hero_class_while_squad_is_in_creation_state_will_throw_an_exception()
    {
        /** @var \App\Domain\Models\Squad $squad */
        $squad = factory(Squad::class)->create();

        foreach (Squad::STARTING_HERO_POST_TYPES as $heroPostType => $count) {
            foreach(range(1, $count) as $heroPostNumber) {
                factory(HeroPost::class)->create([
                    'squad_id' => $squad->id,
                    'hero_post_type_id' => HeroPostType::query()->where('name', '=', $heroPostType)->first()->id
                ]);
            }
        }

        $heroPosts = $squad->heroPosts;
        $this->assertEquals(Squad::getStartingHeroesCount(), $heroPosts->count());

        /*
         * If we have 4 starting hero posts, and 3 required classes, we can create a maximum of 2 of the same class
         */
        $sameHeroClassCountToAdd = (Squad::getStartingHeroesCount() - HeroClass::requiredStarting()->count()) + 1;
        /** @var HeroClass $heroClass */
        $heroClass = HeroClass::requiredStarting()->inRandomOrder()->first();

        foreach(range(1, $sameHeroClassCountToAdd) as $heroToAddCount) {
            /** @var Hero $hero */
            $hero = factory(Hero::class)->create([
                'hero_class_id' => $heroClass->id
            ]);
            /** @var \App\Domain\Models\HeroPost $emptyPost */
            $emptyPost = $heroPosts->postFilled(false)->first();
            $emptyPost->hero_id = $hero->id;
        }

        $this->assertEquals($sameHeroClassCountToAdd, $squad->getHeroes()->count());
        $this->assertTrue($squad->inCreationState());

        try {
            /** @var HeroPost $availableHeroPost */
            $availableHeroPost = $heroPosts->postFilled(false)->first();
            $this->assertNotNull($availableHeroPost);
            $heroRace = $availableHeroPost->heroPostType->heroRaces->first();
            $action = new AddHeroToSquad($squad, $heroRace, $heroClass, 'TestHero' . rand(1,999999));
            $action();
        } catch (InvalidHeroClassException $exception) {
            $this->assertEquals($heroClass, $exception->getHeroClass());
            $this->assertEquals($sameHeroClassCountToAdd, $squad->getHeroes()->count());
            return;
        }

        $this->fail("Exception not thrown");
    }
}

<?php

namespace Tests\Unit;

use App\Domain\Actions\AddHeroToSquad;
use App\Exceptions\HeroPostNotFoundException;
use App\Exceptions\InvalidHeroClassException;
use App\Hero;
use App\HeroClass;
use App\Heroes\HeroPosts\HeroPost;
use App\HeroRace;
use App\Squad;
use App\Squads\HeroClassAvailability;
use App\Squads\HeroPostAvailability;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddHeroToSquadTest extends TestCase
{
    /**
     * @test
     */
    public function adding_a_hero_without_a_hero_post_will_throw_an_exception()
    {
        /** @var Squad $squad */
        $squad = factory(Squad::class)->create();

        $this->assertEquals(0, $squad->heroPosts->count());
        /** @var HeroRace $heroRace */
        $heroRace = HeroRace::query()->inRandomOrder()->first();
        /** @var HeroClass $heroClass */
        $heroClass = HeroClass::query()->inRandomOrder()->first();

        try {
            $action = new AddHeroToSquad($squad, $heroRace, $heroClass, 'TestHero' . rand(1,999999));
            $action->execute();
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
        /** @var HeroPost $heroPost */
        $heroPost = factory(HeroPost::class)->create();
        /** @var HeroRace $heroRace */
        $heroRace = HeroRace::query()->where('id', '!=', $heroPost->hero_race_id)->inRandomOrder()->first();
        /** @var HeroClass $heroClass */
        $heroClass = HeroClass::query()->inRandomOrder()->first();

        try {
            $action = new AddHeroToSquad($heroPost->squad, $heroRace, $heroClass, 'TestHero' . rand(1,999999));
            $action->execute();
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
        /** @var Squad $squad */
        $squad = factory(Squad::class)->create();

        foreach (Squad::STARTING_HERO_POSTS as $heroRaceName => $count) {
            foreach(range(1, $count) as $heroPostNumber) {
                factory(HeroPost::class)->create([
                    'squad_id' => $squad->id,
                    'hero_race_id' => HeroRace::where('name', '=', $heroRaceName)->first()->id
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
            /** @var HeroPost $emptyPost */
            $emptyPost = $heroPosts->postFilled(false)->first();
            $emptyPost->hero_id = $hero->id;
        }

        $this->assertEquals($sameHeroClassCountToAdd, $squad->getHeroes()->count());
        $this->assertTrue($squad->inCreationState());

        try {
            $emptyHeroRace = $heroPosts->postFilled(false)->first()->heroRace;
            $action = new AddHeroToSquad($squad, $emptyHeroRace, $heroClass, 'TestHero' . rand(1,999999));
            $action->execute();
        } catch (InvalidHeroClassException $exception) {
            $this->assertEquals($heroClass, $exception->getHeroClass());
            $this->assertEquals($sameHeroClassCountToAdd, $squad->getHeroes()->count());
            return;
        }

        $this->fail("Exception not thrown");
    }
}

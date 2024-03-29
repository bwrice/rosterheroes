<?php

namespace Tests\Feature;

use App\Domain\Models\Hero;
use App\Domain\Models\HeroClass;
use App\Domain\Models\HeroPost;
use App\Domain\Models\HeroRace;
use App\Domain\Models\Measurable;
use App\Domain\Models\MeasurableType;
use App\Domain\Models\Squad;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SquadHeroControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function a_hero_can_be_created()
    {
        $this->withoutExceptionHandling();

        /** @var Squad $squad */
        $squad = factory(Squad::class)->states('starting-posts')->create();
        $user = Passport::actingAs($squad->user);

        $heroName = 'TestHero' . rand(1,999999);
        $heroRace = HeroRace::HUMAN;
        $heroClass = HeroClass::WARRIOR;

        $response = $this->json('POST','api/v1/squad/' . $squad->slug .  '/heroes', [
            'name' => $heroName,
            'race' => $heroRace,
            'class' => $heroClass
        ]);

        $squad = $squad->fresh();

        $response->assertStatus(201);
        $this->assertEquals(1, $squad->heroes->count());

        /** @var \App\Domain\Models\Hero $hero */
        $hero = $squad->heroes->first();

        $this->assertEquals($heroName, $hero->name);
        $this->assertEquals($heroClass, $hero->heroClass->name);
        $this->assertEquals($heroRace, $hero->heroRace->name);

        /*
         * Assert hero has starting items
         */
        $items = $hero->items;
        $this->assertEquals($hero->heroClass->getBehavior()->getStartItemBlueprints()->count(), $items->count());

        /*
         * Assert hero has measurables
         */
        $measurableTypes = MeasurableType::heroTypes();
        $measurables = $hero->measurables;
        $this->assertEquals($measurableTypes->count(), $measurables->count());
        $measurableTypes->each(function(MeasurableType $measurableType) use ($measurables) {
            $filtered = $measurables->filter(function(Measurable $measurable) use ($measurableType) {
                return $measurable->measurable_type_id == $measurableType->id;
            });

            $this->assertEquals(1, $filtered->count());
        });

    }


    /**
     * @test
     */
    public function it_will_not_create_a_hero_without_an_empty_hero_post()
    {
        $this->withoutExceptionHandling();

        /** @var \App\Domain\Models\Squad $squad */
        $squad = factory(Squad::class)->create();
        $this->assertEquals(0, $squad->heroPosts->count());

        $user = Passport::actingAs($squad->user);

        $heroName = 'TestHero' . rand(1,999999);
        $heroRace = HeroRace::HUMAN;
        $heroClass = HeroClass::WARRIOR;

        try {
            $response = $this->json('POST','api/v1/squad/' . $squad->slug .  '/heroes', [
                'name' => $heroName,
                'race' => $heroRace,
                'class' => $heroClass
            ]);

        } catch (ValidationException $exception) {
            $heroRaceErrors = $exception->validator->errors()->get('race');
            $this->assertNotEmpty($heroRaceErrors);
            $this->assertEquals(0, $squad->heroes->count());
            return;
        }

        $this->fail("Exception Not Thrown");
    }

    /**
     * @test
     */
    public function it_will_not_create_a_hero_without_a_valid_hero_post()
    {
        $this->withoutExceptionHandling();

        /** @var \App\Domain\Models\HeroPost $heroPost */
        $heroPost = factory(HeroPost::class)->create();
        $squad = $heroPost->squad;
        /** @var \App\Domain\Models\HeroRace $invalidHeroRace */
        $validHeroRaceIDs = $heroPost->heroPostType->heroRaces->pluck('id')->toArray();
        // get Hero Race with ID NOT equal to a valid hero race ID
        $invalidHeroRace = HeroRace::query()->whereNotIn('id', $validHeroRaceIDs)->inRandomOrder()->first();
        /** @var \App\Domain\Models\HeroClass $heroClass */
        $heroClass = HeroClass::query()->inRandomOrder()->first();

        $user = Passport::actingAs($squad->user);
        $heroName = 'TestHero' . rand(1,999999);

        try {
            $response = $this->json('POST','api/v1/squad/' . $squad->slug .  '/heroes', [
                'name' => $heroName,
                'race' => $invalidHeroRace->name,
                'class' => HeroClass::RANGER
            ]);
        } catch (ValidationException $exception) {
            $heroRaceErrors = $exception->validator->errors()->get('race');
            $this->assertNotEmpty($heroRaceErrors);
            $this->assertEquals(0, $squad->heroes->count());
            return;
        }

        $this->fail("Exception Not Thrown");
    }

    /**
     * @test
     */
    public function it_will_not_create_a_hero_for_an_invalid_hero_class_while_in_creation_state()
    {
        $this->withoutExceptionHandling();

        /** @var Squad $squad */
        $squad = factory(Squad::class)->states('starting-posts')->create();

        $heroPosts = $squad->heroPosts;
        $this->assertEquals(Squad::getStartingHeroesCount(), $heroPosts->count());

        /*
         * If we have 4 starting hero posts, and 3 required classes, we can create a maximum of 2 of the same class
         */
        $sameHeroClassCountToAdd = (Squad::getStartingHeroesCount() - HeroClass::requiredStarting()->count()) + 1;
        /** @var HeroClass $overloadedHeroClass */
        $overloadedHeroClass = HeroClass::requiredStarting()->inRandomOrder()->first();
        $usedHeroRaceIDs = [];

        foreach(range(1, $sameHeroClassCountToAdd) as $heroToAddCount) {

            /*
             * We have to make sure we're using a difference race when testing hero class, otherwise
             * the hero posts won't be filled correctly, potentially throwing off the available empty hero posts
             */
            $heroRace = HeroRace::query()->whereNotIn('id', $usedHeroRaceIDs)->inRandomOrder()->first();
            $usedHeroRaceIDs[] = $heroRace->id;

            /** @var Hero $hero */
            $hero = factory(Hero::class)->create([
                'hero_class_id' => $overloadedHeroClass->id,
                'hero_race_id' => $heroRace->id,
                'squad_id' => $squad->id
            ]);
        }

        $squad = $squad->fresh();
        $heroesWithOverloadedHeroClass = $squad->heroes->filterByClass($overloadedHeroClass);
        $this->assertEquals($sameHeroClassCountToAdd, $heroesWithOverloadedHeroClass->count());
        $this->assertTrue($squad->inCreationState());

        $user = Passport::actingAs($squad->user);
        $heroName = 'TestHero' . rand(1,999999);

        try {
            // Once again make sure to use a unique hero race
            $heroRaceForNewHero = HeroRace::query()->whereNotIn('id', $usedHeroRaceIDs)->inRandomOrder()->first();
            $response = $this->json('POST','api/v1/squad/' . $squad->slug .  '/heroes', [
                'name' => $heroName,
                'race' => $heroRaceForNewHero->name,
                'class' => $overloadedHeroClass->name
            ]);
        } catch (ValidationException $exception) {
            $heroRaceErrors = $exception->validator->errors()->get('class');
            $this->assertNotEmpty($heroRaceErrors);
            // We should only have the original heroes for the overloaded hero class
            $this->assertEquals($sameHeroClassCountToAdd, $squad->heroes->count());
            return;
        }

        $this->fail("Exception Not Thrown");
    }

    /**
     * @test
     */
    public function it_will_not_create_a_hero_with_white_space_name()
    {
        $this->withoutExceptionHandling();

        /** @var Squad $squad */
        $squad = factory(Squad::class)->states('starting-posts')->create();

        $user = Passport::actingAs($squad->user);
        $whiteSpaceName = '        A';
        $heroClassName = HeroClass::requiredStarting()->inRandomOrder()->first()->name;
        $heroRaceName = HeroRace::starting()->inRandomOrder()->first()->name;

        $this->assertEquals(0, $squad->heroes()->count());
        try {
            $response = $this->json('POST','api/v1/squad/' . $squad->slug .  '/heroes', [
                'name' => $whiteSpaceName,
                'race' => $heroRaceName,
                'class' => $heroClassName
            ]);
        } catch (ValidationException $exception) {
            $nameErrors = $exception->validator->errors()->get('name');
            $this->assertNotEmpty($nameErrors);
            $this->assertEquals(0, $squad->heroes()->count());
            return;
        }

        $this->fail("Exception Not Thrown");
    }

    /**
     * @test
     */
    public function it_will_not_create_a_hero_with_special_characters()
    {
        $this->withoutExceptionHandling();

        /** @var Squad $squad */
        $squad = factory(Squad::class)->states('starting-posts')->create();

        $user = Passport::actingAs($squad->user);
        $specialCharacterName = 'hero' . rand(1, 999999) . '&'; // add ampersand (special char) at end of hero name
        $heroClassName = HeroClass::requiredStarting()->inRandomOrder()->first()->name;
        $heroRaceName = HeroRace::starting()->inRandomOrder()->first()->name;

        $this->assertEquals(0, $squad->heroes()->count());
        try {
            $response = $this->json('POST','api/v1/squad/' . $squad->slug .  '/heroes', [
                'name' => $specialCharacterName,
                'race' => $heroRaceName,
                'class' => $heroClassName
            ]);
        } catch (ValidationException $exception) {
            $nameErrors = $exception->validator->errors()->get('name');
            $this->assertNotEmpty($nameErrors);
            $this->assertEquals(0, $squad->heroes()->count());
            return;
        }

        $this->fail("Exception Not Thrown");
    }

    /**
     * @test
     */
    public function it_will_return_a_squads_heroes()
    {
        $this->withoutExceptionHandling();

        /** @var Hero $heroOne */
        $heroOne = factory(Hero::class)->state('with-measurables')->create();

        $squad = $heroOne->squad;


        /** @var Hero $heroTwo */
        $heroTwo = factory(Hero::class)->state('with-measurables')->create([
            'squad_id' => $squad->id
        ]);

        Passport::actingAs($squad->user);

        $response = $this->get('/api/v1/squads/' . $squad->slug . '/heroes');

        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    [
                        'uuid' => $heroOne->uuid
                    ],
                    [
                        'uuid' => $heroTwo->uuid
                    ]
                ]
            ]);
    }
}

<?php

namespace Tests\Unit;

use App\Domain\Actions\AddNewHeroToSquadAction;
use App\Domain\Models\HeroClass;
use App\Domain\Models\HeroRace;
use App\Domain\Models\ItemBlueprint;
use App\Domain\Models\Squad;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddNewHeroToSquadActionTest extends TestCase
{
    use DatabaseTransactions;

    /** @var AddNewHeroToSquadAction */
    protected $domainAction;

    /** @var Squad */
    protected $squad;

    public function setUp(): void
    {
        parent::setUp();

        $this->domainAction = app(AddNewHeroToSquadAction::class);

        $this->squad = factory(Squad::class)->states('starting-posts')->create();
    }

    /**
     * @test
     */
    public function it_will_attach_a_new_hero()
    {
        $heroName = 'TestHero' . rand(1,999999);
        /** @var HeroClass $heroClass */
        $heroClass = HeroClass::query()->inRandomOrder()->first();
        /** @var HeroRace $heroRace */
        $heroRace = HeroRace::query()->inRandomOrder()->first();
        $hero = $this->domainAction->execute($this->squad, $heroName, $heroClass, $heroRace);
        $this->assertEquals($hero->squad->id, $this->squad->id);
    }

    /**
     * @test
     * @dataProvider provides_it_will_attach_starting_items_to_a_new_hero
     * @param $heroClassName
     * @throws \Exception
     */
    public function it_will_attach_starting_items_to_a_new_hero($heroClassName)
    {
        $heroName = 'TestHero' . rand(1,999999);
        /** @var HeroClass $heroClass */
        $heroClass = HeroClass::query()->where('name', '=', $heroClassName)->first();
        /** @var HeroRace $heroRace */
        $heroRace = HeroRace::query()->inRandomOrder()->first();

        $blueprints = $heroClass->getBehavior()->getStartItemBlueprints();
        $this->assertGreaterThan(0, $blueprints->count());

        $hero = $this->domainAction->execute($this->squad, $heroName, $heroClass, $heroRace);
        $items = $hero->items();

        $this->assertEquals($blueprints->count(), $items->count());

        $itemTypeIDs = $items->pluck('item_type_id')->values()->toArray();

        $blueprints->each(function (ItemBlueprint $itemBlueprint) use ($itemTypeIDs) {
            $blueprintItemTypeIDs = $itemBlueprint->itemTypes()->pluck('id')->toArray();
            $intersect = array_intersect($blueprintItemTypeIDs, $itemTypeIDs);
            $this->assertGreaterThan(0, count($intersect));
        });
    }

    public function provides_it_will_attach_starting_items_to_a_new_hero()
    {
        return [
            HeroClass::WARRIOR => [
                'hero_class_name' => HeroClass::WARRIOR
            ],
            HeroClass::RANGER => [
                'hero_class_name' => HeroClass::RANGER
            ],
            HeroClass::SORCERER => [
                'hero_class_name' => HeroClass::SORCERER
            ]
        ];
    }
}

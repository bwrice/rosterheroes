<?php

namespace Tests\Feature;

use App\Domain\Models\Hero;
use App\Domain\Models\PlayerSpirit;
use App\Domain\Models\Squad;
use App\Facades\HeroService;
use App\Facades\SquadFacade;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SquadServiceTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function combat_ready_will_return_false_if_there_are_no_heroes()
    {
        /** @var Squad $squad */
        $squad = factory(Squad::class)->create();
        $this->assertEquals(0, $squad->heroes->count());
        $this->assertFalse(SquadFacade::combatReady($squad));
    }

    /**
     * @test
     */
    public function combat_ready_will_return_false_if_no_heroes_are_combat_ready()
    {
        /** @var Squad $squad */
        $squad = factory(Squad::class)->create();
        /** @var Hero $heroOne */
        $heroOne = factory(Hero::class)->create([
            'squad_id' => $squad->id
        ]);
        $heroTwo = factory(Hero::class)->create([
            'squad_id' => $squad->id,
        ]);
        HeroService::partialMock()->shouldReceive('combatReady')->andReturn(false);
        $this->assertFalse(SquadFacade::combatReady($squad));
    }

    /**
     * @test
     */
    public function combat_ready_will_return_true_if_just_one_hero_is_ready()
    {
        /** @var Squad $squad */
        $squad = factory(Squad::class)->create();
        /** @var Hero $heroOne */
        $heroOne = factory(Hero::class)->create([
            'squad_id' => $squad->id
        ]);
        $heroTwo = factory(Hero::class)->create([
            'squad_id' => $squad->id,
        ]);
        HeroService::partialMock()->shouldReceive('combatReady')->andReturnUsing(function (Hero $hero) use ($heroOne) {
            if ($hero->id === $heroOne->id) {
                return true;
            }
            return false;
        });
        $this->assertTrue(SquadFacade::combatReady($squad));
    }
}

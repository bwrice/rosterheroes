<?php

namespace Tests\Feature;

use App\Domain\Actions\AddNewHeroToSquadAction;
use App\Domain\Actions\NPC\CreateNPCHero;
use App\Domain\Models\HeroClass;
use App\Domain\Models\HeroRace;
use App\Facades\NPC;
use App\Facades\SquadFacade;
use App\Factories\Models\HeroFactory;
use App\Factories\Models\SquadFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class CreateNPCHeroTest extends NPCActionTest
{
    use DatabaseTransactions;

    /**
     * @return CreateNPCHero
     */
    protected function getDomainAction()
    {
        return app(CreateNPCHero::class);
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_squad_is_not_in_creation_state()
    {
        $squad = SquadFactory::new()->create();
        /** @var HeroRace $heroRace */
        $heroRace = HeroRace::query()->inRandomOrder()->first();
        /** @var HeroClass $heroClass */
        $heroClass = HeroClass::query()->inRandomOrder()->first();

        NPC::partialMock()->shouldReceive('isNPC')->andReturn(true);
        SquadFacade::partialMock()->shouldReceive('inCreationState')->andReturn(false);

        try {
            $this->getDomainAction()->execute($squad, $heroRace, $heroClass);
        } catch (\Exception $exception) {
            $this->assertEquals(CreateNPCHero::EXCEPTION_CODE_SQUAD_INVALID_STATE, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_execute_add_new_hero_to_squad_domain_action()
    {
        $squad = SquadFactory::new()->create();
        /** @var HeroRace $heroRace */
        $heroRace = HeroRace::query()->inRandomOrder()->first();
        /** @var HeroClass $heroClass */
        $heroClass = HeroClass::query()->inRandomOrder()->first();

        NPC::partialMock()->shouldReceive('isNPC')->andReturn(true);
        SquadFacade::partialMock()->shouldReceive('inCreationState')->andReturn(true);

        $heroName = Str::random();
        NPC::partialMock()->shouldReceive('heroName')->andReturn($heroName);

        $returnValue = HeroFactory::new()->create();

        $mock = \Mockery::spy(AddNewHeroToSquadAction::class)
            ->shouldReceive('execute')
            ->with($squad, $heroName, $heroClass, $heroRace)
            ->andReturn($returnValue)
            ->getMock();

        $this->instance(AddNewHeroToSquadAction::class, $mock);

        $hero = $this->getDomainAction()->execute($squad, $heroRace, $heroClass);
        $this->assertEquals($returnValue->id, $hero->id);
    }
}

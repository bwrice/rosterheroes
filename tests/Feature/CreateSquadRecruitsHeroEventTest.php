<?php

namespace Tests\Feature;

use App\Domain\Actions\ProvinceEvents\CreateSquadRecruitsHeroEvent;
use App\Domain\Behaviors\ProvinceEvents\SquadRecruitsHeroBehavior;
use App\Domain\Models\ProvinceEvent;
use App\Domain\Models\Week;
use App\Events\NewProvinceEvent;
use App\Factories\Models\HeroFactory;
use App\Factories\Models\QuestFactory;
use App\Factories\Models\RecruitmentCampFactory;
use App\Factories\Models\SquadFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CreateSquadRecruitsHeroEventTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return CreateSquadRecruitsHeroEvent
     */
    protected function getDomainAction()
    {
        return app(CreateSquadRecruitsHeroEvent::class);
    }

    /**
     * @test
     */
    public function it_will_create_a_squad_recruits_hero_province_event()
    {
        $squad = SquadFactory::new()->create();
        $hero = HeroFactory::new()->withSquadID($squad->id)->create();
        $recruitmentCamp = RecruitmentCampFactory::new()->create();

        $provinceEvent = $this->getDomainAction()->execute($squad, $hero, $recruitmentCamp, $recruitmentCamp->province, now());
        $this->assertEquals(ProvinceEvent::TYPE_SQUAD_RECRUITS_HERO, $provinceEvent->event_type);
        $this->assertEquals($squad->id, $provinceEvent->squad_id);
        $this->assertEquals($recruitmentCamp->province->id, $provinceEvent->province_id);
        /** @var SquadRecruitsHeroBehavior $behavior */
        $behavior = $provinceEvent->getBehavior();
        $this->assertEquals((string) $hero->uuid, $behavior->getHeroUuid());
        $this->assertEquals((string) $recruitmentCamp->uuid, $behavior->getRecruitmentCampUuid());
    }

    /**
     * @test
     * @throws \Exception
     */
    public function it_will_dispatch_new_province_event_when_creating_squad_recruits_hero_event()
    {
        $squad = SquadFactory::new()->create();
        $hero = HeroFactory::new()->withSquadID($squad->id)->create();
        $recruitmentCamp = RecruitmentCampFactory::new()->create();

        Event::fake();
        $this->getDomainAction()->execute($squad, $hero, $recruitmentCamp, $recruitmentCamp->province, now());

        Event::assertDispatched(NewProvinceEvent::class);
    }
}

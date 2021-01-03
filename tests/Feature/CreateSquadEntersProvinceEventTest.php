<?php

namespace Tests\Feature;

use App\Domain\Actions\ProvinceEvents\CreateSquadEntersProvinceEvent;
use App\Domain\Behaviors\ProvinceEvents\SquadEntersProvinceBehavior;
use App\Domain\Models\Json\ProvinceEventData\SquadEntersProvince;
use App\Domain\Models\Province;
use App\Domain\Models\ProvinceEvent;
use App\Events\ProvinceEventCreated;
use App\Factories\Models\ProvinceEventFactory;
use App\Factories\Models\SquadFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CreateSquadEntersProvinceEventTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return CreateSquadEntersProvinceEvent
     */
    protected function getDomainAction()
    {
        return app(CreateSquadEntersProvinceEvent::class);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function it_will_create_a_squad_enters_province_event()
    {
        /** @var Province $provinceEntered */
        $provinceEntered = Province::query()->inRandomOrder()->first();
        /** @var Province $provinceLeft */
        $provinceLeft = $provinceEntered->borders()->inRandomOrder()->first();
        $squad = SquadFactory::new()->create();
        $now = now();
        $cost = rand(10, 1000);

        $event = $this->getDomainAction()->execute($provinceEntered, $provinceLeft, $squad, $now, $cost);
        $this->assertEquals(ProvinceEvent::TYPE_SQUAD_ENTERS_PROVINCE, $event->event_type);
        $this->assertEquals($provinceEntered->id, $event->province_id);

        /** @var SquadEntersProvinceBehavior $behavior */
        $behavior = $event->getBehavior();
        $this->assertEquals($squad->id, $event->squad->id);
        $this->assertEquals($cost, $behavior->getGoldCost());
        $this->assertEquals($provinceLeft->uuid, $behavior->getProvinceLeftUuid());
    }

    /**
     * @test
     * @throws \Exception
     */
    public function it_will_dispatch_province_event_created_if_new_squad_enters_province_event_created()
    {
        /** @var Province $provinceEntered */
        $provinceEntered = Province::query()->inRandomOrder()->first();
        /** @var Province $provinceLeft */
        $provinceLeft = $provinceEntered->borders()->inRandomOrder()->first();
        $squad = SquadFactory::new()->create();

        Event::fake();
        $this->getDomainAction()->execute($provinceEntered, $provinceLeft, $squad, now(), rand(10, 1000));

        Event::assertDispatched(ProvinceEventCreated::class);
    }
}

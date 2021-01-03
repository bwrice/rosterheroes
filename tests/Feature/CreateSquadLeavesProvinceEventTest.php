<?php

namespace Tests\Feature;

use App\Domain\Actions\ProvinceEvents\CreateSquadLeavesProvinceEvent;
use App\Domain\Behaviors\ProvinceEvents\SquadLeavesProvinceBehavior;
use App\Domain\Models\Json\ProvinceEventData\SquadEntersProvince;
use App\Domain\Models\Json\ProvinceEventData\SquadLeavesProvince;
use App\Domain\Models\Province;
use App\Domain\Models\ProvinceEvent;
use App\Events\ProvinceEventCreated;
use App\Factories\Models\SquadFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CreateSquadLeavesProvinceEventTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return CreateSquadLeavesProvinceEvent
     */
    protected function getDomainAction()
    {
        return app(CreateSquadLeavesProvinceEvent::class);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function it_will_create_a_squad_enters_province_event()
    {
        /** @var Province $provinceLeft */
        $provinceLeft = Province::query()->inRandomOrder()->first();
        /** @var Province $provinceEntered */
        $provinceEntered = $provinceLeft->borders()->inRandomOrder()->first();
        $squad = SquadFactory::new()->create();
        $now = now();

        $event = $this->getDomainAction()->execute($provinceLeft, $provinceEntered, $squad, $now);
        $this->assertEquals(ProvinceEvent::TYPE_SQUAD_LEAVES_PROVINCE, $event->event_type);
        $this->assertEquals($provinceLeft->id, $event->province_id);

        /** @var SquadLeavesProvinceBehavior $behavior */
        $behavior = $event->getBehavior();
        $this->assertEquals($squad->id, $event->squad->id);
        $this->assertEquals($provinceEntered->uuid, $behavior->getProvinceToUuid());
    }

    /**
     * @test
     * @throws \Exception
     */
    public function it_will_dispatch_province_event_created_if_new_squad_enters_province_event_created()
    {
        /** @var Province $provinceLeft */
        $provinceLeft = Province::query()->inRandomOrder()->first();
        /** @var Province $provinceEntered */
        $provinceEntered = $provinceLeft->borders()->inRandomOrder()->first();
        $squad = SquadFactory::new()->create();

        Event::fake();
        $this->getDomainAction()->execute($provinceLeft, $provinceEntered, $squad, now());

        Event::assertDispatched(ProvinceEventCreated::class);
    }
}

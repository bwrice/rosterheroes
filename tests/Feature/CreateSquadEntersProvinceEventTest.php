<?php

namespace Tests\Feature;

use App\Domain\Actions\ProvinceEvents\CreateSquadEntersProvinceEvent;
use App\Domain\Models\Json\ProvinceEventData\SquadEntersProvince;
use App\Domain\Models\Province;
use App\Domain\Models\ProvinceEvent;
use App\Factories\Models\ProvinceEventFactory;
use App\Factories\Models\SquadFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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

        /** @var SquadEntersProvince $eventData */
        $eventData = $event->getEventData();
        $this->assertEquals($squad->id, $event->squad->id);
        $this->assertEquals($cost, $eventData->getGoldCost());
        $this->assertEquals($provinceLeft->uuid, $eventData->getProvinceLeftUuid());
    }

    /**
     * @test
     */
    public function it_will_not_create_a_squad_enters_province_event_if_a_recent_event_for_squad_and_province_exists()
    {
        $minutesAgo = CreateSquadEntersProvinceEvent::MINUTES_BETWEEN_EVENTS - 1;
        $existingEvent = ProvinceEventFactory::new()->at(now()->subMinutes($minutesAgo))->squadEntersProvince()->create();

        $provinceEntered = $existingEvent->province;
        $provinceLeft = $provinceEntered->borders()->inRandomOrder()->first();
        $squad = $existingEvent->squad;

        $eventsCount = ProvinceEvent::query()->where('event_type', '=', ProvinceEvent::TYPE_SQUAD_ENTERS_PROVINCE)
            ->where('province_id', '=', $provinceEntered->id
            )->where('squad_id', '=', $squad->id)->count();
        $this->assertEquals(1, $eventsCount);

        $newEvent = $this->getDomainAction()->execute($provinceEntered, $provinceLeft, $squad, now(), rand(10, 9999));
        $this->assertEquals($existingEvent->uuid, $newEvent->uuid);

        $eventsCount = ProvinceEvent::query()->where('event_type', '=', ProvinceEvent::TYPE_SQUAD_ENTERS_PROVINCE)
            ->where('province_id', '=', $provinceEntered->id
            )->where('squad_id', '=', $squad->id)->count();
        $this->assertEquals(1, $eventsCount);
    }

    /**
     * @test
     */
    public function it_will_create_a_new_squad_enters_province_event_if_a_NON_recent_event_for_squad_and_province_exists()
    {
        $minutesAgo = CreateSquadEntersProvinceEvent::MINUTES_BETWEEN_EVENTS + 5;
        $existingEvent = ProvinceEventFactory::new()->at(now()->subMinutes($minutesAgo))->squadEntersProvince()->create();

        $provinceEntered = $existingEvent->province;
        $provinceLeft = $provinceEntered->borders()->inRandomOrder()->first();
        $squad = $existingEvent->squad;

        $eventsCount = ProvinceEvent::query()->where('event_type', '=', ProvinceEvent::TYPE_SQUAD_ENTERS_PROVINCE)
            ->where('province_id', '=', $provinceEntered->id
            )->where('squad_id', '=', $squad->id)->count();
        $this->assertEquals(1, $eventsCount);

        $newEvent = $this->getDomainAction()->execute($provinceEntered, $provinceLeft, $squad, now(), rand(10, 9999));
        $this->assertNotEquals($existingEvent->uuid, $newEvent->uuid);

        $eventsCount = ProvinceEvent::query()->where('event_type', '=', ProvinceEvent::TYPE_SQUAD_ENTERS_PROVINCE)
            ->where('province_id', '=', $provinceEntered->id
            )->where('squad_id', '=', $squad->id)->count();
        $this->assertEquals(2, $eventsCount);
    }
}

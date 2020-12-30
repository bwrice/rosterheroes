<?php

namespace Tests\Feature;

use App\Domain\Actions\ProvinceEvents\CreateSquadEntersProvinceEvent;
use App\Domain\Models\Json\ProvinceEventData\SquadEntersProvince;
use App\Domain\Models\Province;
use App\Domain\Models\ProvinceEvent;
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
     */
    public function it_will_create_a_squad_events_province_event()
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

        /** @var SquadEntersProvince $data */
        $data = $event->data;
        $this->assertEquals($squad->uuid, $data->squadUuid());
        $this->assertEquals($cost, $data->getGoldCost());
        $this->assertEquals($provinceLeft->uuid, $data->getProvinceLeftUuid());
    }
}

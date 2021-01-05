<?php

namespace Tests\Feature;

use App\Domain\Actions\ProvinceEvents\CreateSquadJoinsQuestEvent;
use App\Domain\Behaviors\ProvinceEvents\SquadJoinsQuestBehavior;
use App\Domain\Models\Province;
use App\Domain\Models\ProvinceEvent;
use App\Domain\Models\Week;
use App\Events\NewProvinceEvent;
use App\Factories\Models\ProvinceEventFactory;
use App\Factories\Models\QuestFactory;
use App\Factories\Models\SquadFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CreateSquadJoinsQuestEventTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return CreateSquadJoinsQuestEvent
     */
    protected function getDomainAction()
    {
        return app(CreateSquadJoinsQuestEvent::class);
    }

    /**
     * @test
     */
    public function it_will_create_a_province_event_of_type_squad_joins_quest()
    {
        $squad = SquadFactory::new()->create();
        $quest = QuestFactory::new()->create();
        $week = factory(Week::class)->create();

        $provinceEvent = $this->getDomainAction()->execute($squad, $quest, $quest->province, $week, now());
        $this->assertEquals(ProvinceEvent::TYPE_SQUAD_JOINS_QUEST, $provinceEvent->event_type);
        $this->assertEquals($squad->id, $provinceEvent->squad_id);
        $this->assertEquals($quest->province->id, $provinceEvent->province_id);

        /** @var SquadJoinsQuestBehavior $behavior */
        $behavior = $provinceEvent->getBehavior();
        $this->assertEquals($quest->uuid, $behavior->getQuestUuid());
    }

    /**
     * @test
     * @throws \Exception
     */
    public function it_will_dispatch_province_event_created_if_new_squad_joins_quest_event_created()
    {
        $squad = SquadFactory::new()->create();
        $quest = QuestFactory::new()->create();
        $week = factory(Week::class)->create();

        Event::fake();
        $this->getDomainAction()->execute($squad, $quest, $quest->province, $week, now());

        Event::assertDispatched(NewProvinceEvent::class);
    }

    /**
     * @test
     */
    public function it_will_create_a_new_event_if_same_squad_joins_quest_event_exists_for_different_week()
    {
        $quest = QuestFactory::new()->create();
        $week = factory(Week::class)->create();
        $oldProvinceEvent = ProvinceEventFactory::new()->squadJoinsQuest($quest, $week)->create();

        $diffWeek = factory(Week::class)->create();
        $newProvinceEvent = $this->getDomainAction()->execute($oldProvinceEvent->squad, $quest, $oldProvinceEvent->province, $diffWeek, now());

        $this->assertNotEquals($oldProvinceEvent->id, $newProvinceEvent->id);
    }

    /**
     * @test
     */
    public function it_will_update_an_existing_squad_joins_quest_event_when_creating_an_event_for_the_same_week()
    {
        $quest = QuestFactory::new()->create();
        $week = factory(Week::class)->create();
        $now = now();
        $oldProvinceEvent = ProvinceEventFactory::new()->squadJoinsQuest($quest, $week)->at($now->subHour())->create();
        $oldTimeStamp = $oldProvinceEvent->happened_at->timestamp;

        $newProvinceEvent = $this->getDomainAction()->execute($oldProvinceEvent->squad, $quest, $oldProvinceEvent->province, $week, $now);

        $this->assertEquals($oldProvinceEvent->id, $newProvinceEvent->id);
        $this->assertGreaterThan($oldTimeStamp, $newProvinceEvent->happened_at->timestamp);
    }
}

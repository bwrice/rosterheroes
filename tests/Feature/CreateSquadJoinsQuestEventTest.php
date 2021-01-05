<?php

namespace Tests\Feature;

use App\Domain\Actions\ProvinceEvents\CreateSquadJoinsQuestEvent;
use App\Domain\Behaviors\ProvinceEvents\SquadJoinsQuestBehavior;
use App\Domain\Models\ProvinceEvent;
use App\Factories\Models\QuestFactory;
use App\Factories\Models\SquadFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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

        $provinceEvent = $this->getDomainAction()->execute($squad, $quest, $quest->province);
        $this->assertEquals(ProvinceEvent::TYPE_SQUAD_JOINS_QUEST, $provinceEvent->event_type);
        $this->assertEquals($squad->id, $provinceEvent->squad_id);
        $this->assertEquals($quest->province->id, $provinceEvent->province_id);

        /** @var SquadJoinsQuestBehavior $behavior */
        $behavior = $provinceEvent->getBehavior();
        $this->assertEquals($quest->uuid, $behavior->getQuestUuid());
    }
}

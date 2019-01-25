<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SquadQuestUnitTest extends TestCase
{
    /**
     * @test
     */
    public function joining_a_quest_on_a_continent_not_of_the_current_campaign_will_throw_an_exception()
    {
        $this->fail();
    }

    /**
     * @test
     */
    public function joining_a_quest_when_the_quests_per_week_count_has_been_reached_will_throw_an_exception()
    {
        $this->fail();
    }

    /**
     * @test
     */
    public function joining_a_quest_after_the_week_has_locked_will_throw_an_exception()
    {
        $this->fail();
    }
}

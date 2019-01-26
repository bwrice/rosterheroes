<?php

namespace Tests\Feature;

use App\Campaign;
use App\Continent;
use App\Province;
use App\Campaigns\Quests\Quest;
use App\Squad;
use App\Weeks\Week;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
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
        /** @var \App\Campaigns\Quests\Quest $quest */
        $quest = factory(Quest::class)->create();
        $provinceID = $quest->province->id;
        /** @var Squad $squad */
        $squad = factory(Squad::class)->create([
            'province_id' => $provinceID
        ]);

        /** @var Week $week */
        $week = factory(Week::class)->create();
        Week::setTestCurrent($week);
        Carbon::setTestNow($week->everything_locks_at->copy()->subDays(1));

        $diffContinent = Continent::query()->whereDoesntHave('provinces', function (Builder $builder) use ($provinceID) {
            return $builder->where('id', '=', $provinceID);
        })->inRandomOrder()->first();

        $this->assertNotEquals($quest->province->continent_id, $diffContinent->id);

        /** @var Campaign $campaign */
        $campaign = factory(Campaign::class)->create([
            'squad_id' => $squad->id,
            'week_id' => $week->id,
            'continent_id' => $diffContinent->id
        ]);

        try {
            $squad->joinQuest($quest);
        } catch ( \Exception $exception ) {

            $this->assertEquals(0, $campaign->quests->count());
            return;
        }

        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function joining_a_quest_when_the_quests_per_week_count_has_been_reached_will_throw_an_exception()
    {
        /** @var Week $week */
        $week = factory(Week::class)->create();
        Week::setTestCurrent($week);
        Carbon::setTestNow($week->everything_locks_at->copy()->subDays(1));

        /** @var Squad $squad */
        $squad = factory(Squad::class)->create();

        /** @var Campaign $campaign */
        $campaign = factory(Campaign::class)->create([
            'squad_id' => $squad->id,
            'week_id' => $week->id
        ]);

        $provinces = $campaign->continent->provinces;

        $count = 1;
        while($count <= $squad->getQuestsPerWeekAllowed()) {
            $province = $provinces->random();
            $quest = factory(Quest::class)->create([
                'province_id' => $province->id
            ]);

            $campaign->quests()->attach($quest->id);
            $count++;
        }

        $this->assertEquals($squad->getQuestsPerWeekAllowed(), $campaign->load('quests')->quests->count());

        $provinceForQuestToJoin = $provinces->random();

        /** @var \App\Campaigns\Quests\Quest $questToJoin */
        $questToJoin = factory(Quest::class)->create([
            'province_id' => $provinceForQuestToJoin->id
        ]);
        $squad->province_id = $provinceForQuestToJoin->id;
        $squad->save();

        try {
            $squad->joinQuest($questToJoin);
        } catch ( \Exception $exception ) {

            $this->assertEquals($squad->getQuestsPerWeekAllowed(), $campaign->quests->count());
            return;
        }

        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function joining_a_quest_after_the_week_has_locked_will_throw_an_exception()
    {
        /** @var Week $week */
        $week = factory(Week::class)->create();
        Week::setTestCurrent($week);

        //Set time to after the week locks
        Carbon::setTestNow($week->everything_locks_at->copy()->addMinutes(10));

        /** @var Squad $squad */
        $squad = factory(Squad::class)->create();

        $quest = factory(Quest::class)->create([
            'province_id' => $squad->province->id
        ]);

        try {
            $squad->joinQuest($quest);
        } catch ( \Exception $exception ) {

            $this->assertEquals(0, $squad->campaigns()->first()->quests->count());
            return;
        }

        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function joining_a_completed_quest_will_throw_an_exception()
    {

        /** @var Week $week */
        $week = factory(Week::class)->create();
        Week::setTestCurrent($week);
        Carbon::setTestNow($week->everything_locks_at->copy()->subDays(1));

        /** @var Squad $squad */
        $squad = factory(Squad::class)->create();

        $quest = factory(Quest::class)->create([
            'province_id' => $squad->province->id,
            'completed_at' => $week->everything_locks_at->copy()->subWeeks(1)
        ]);
        
        try {
            $squad->joinQuest($quest);
        } catch ( \Exception $exception ) {

            $this->assertEquals(0, $squad->campaigns()->first()->quests->count());
            return;
        }

        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function joining_a_quest_not_at_the_squads_current_province_will_throw_an_exception()
    {

        /** @var Week $week */
        $week = factory(Week::class)->create();
        Week::setTestCurrent($week);
        Carbon::setTestNow($week->everything_locks_at->copy()->subDays(1));

        /** @var Squad $squad */
        $squad = factory(Squad::class)->create();

        $questProvince = Province::query()->where('id', '!=', $squad->province->id)->inRandomOrder()->first();

        $quest = factory(Quest::class)->create([
            'province_id' => $questProvince->id
        ]);

        try {
            $squad->joinQuest($quest);
        } catch ( \Exception $exception ) {

            $this->assertEquals(0, $squad->campaigns()->first()->quests->count());
            return;
        }

        $this->fail("Exception not thrown");
    }
}

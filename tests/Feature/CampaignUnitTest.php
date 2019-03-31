<?php

namespace Tests\Feature;

use App\Domain\Models\Campaign;
use App\Domain\Models\Continent;
use App\Exceptions\InvalidContinentException;
use App\Exceptions\InvalidProvinceException;
use App\Exceptions\MaxQuestsException;
use App\Exceptions\QuestCompletedException;
use App\Exceptions\QuestExistsException;
use App\Exceptions\WeekLockedException;
use App\Domain\Models\Province;
use App\Domain\Models\Quest;
use App\Domain\Models\Squad;
use App\Domain\Models\Week;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CampaignUnitTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function adding_a_quest_on_a_continent_not_of_the_current_campaign_will_throw_an_exception()
    {
        /** @var \App\Domain\Models\Quest $quest */
        $quest = factory(Quest::class)->create();
        $provinceID = $quest->province->id;

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
            'week_id' => $week->id,
            'continent_id' => $diffContinent->id
        ]);

        $campaign->squad->province_id = $provinceID;
        $campaign->squad->save();

        try {
            $campaign->addQuest($quest);
        } catch (InvalidContinentException $exception) {

            $this->assertEquals(0, $campaign->fresh()->quests->count());
            return;
        }

        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function adding_a_quest_when_the_quests_per_week_count_has_been_reached_will_throw_an_exception()
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

        /** @var \App\Domain\Models\Quest $questToJoin */
        $questToJoin = factory(Quest::class)->create([
            'province_id' => $provinceForQuestToJoin->id
        ]);
        $squad->province_id = $provinceForQuestToJoin->id;
        $squad->save();

        try {
            $campaign->addQuest($questToJoin);
        } catch (MaxQuestsException $exception) {

            $this->assertEquals($squad->getQuestsPerWeekAllowed(), $campaign->fresh()->quests->count());
            return;
        }

        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function adding_a_quest_after_the_week_has_locked_will_throw_an_exception()
    {
        /** @var \App\Domain\Models\Week $week */
        $week = factory(Week::class)->create();
        Week::setTestCurrent($week);

        //Set time to after the week locks
        Carbon::setTestNow($week->everything_locks_at->copy()->addMinutes(10));


        /** @var Campaign $campaign */
        $campaign = factory(Campaign::class)->create([
            'week_id' => $week->id
        ]);

        $provinceID = $campaign->continent->provinces()->inRandomOrder()->first()->id;

        $campaign->squad->province_id = $provinceID;
        $campaign->squad->save();

        $quest = factory(Quest::class)->create([
            'province_id' => $provinceID
        ]);

        try {
            $campaign->addQuest($quest);
        } catch (WeekLockedException $exception) {

            $this->assertEquals(0, $campaign->fresh()->quests->count());
            return;
        }

        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function adding_a_completed_quest_will_throw_an_exception()
    {

        /** @var Week $week */
        $week = factory(Week::class)->create();
        Week::setTestCurrent($week);
        Carbon::setTestNow($week->everything_locks_at->copy()->subDays(1));


        /** @var Campaign $campaign */
        $campaign = factory(Campaign::class)->create([
            'week_id' => $week->id
        ]);

        $provinceID = $campaign->continent->provinces()->inRandomOrder()->first()->id;

        $quest = factory(Quest::class)->create([
            'province_id' => $provinceID,
            'completed_at' => $week->everything_locks_at->copy()->subWeeks(1)
        ]);

        try {
            $campaign->addQuest($quest);
        } catch (QuestCompletedException $exception) {

            $this->assertEquals(0, $campaign->fresh()->quests->count());
            return;
        }

        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function adding_a_quest_not_at_the_squads_current_province_will_throw_an_exception()
    {
        /** @var \App\Domain\Models\Week $week */
        $week = factory(Week::class)->create();
        Week::setTestCurrent($week);
        Carbon::setTestNow($week->everything_locks_at->copy()->subDays(1));


        /** @var Campaign $campaign */
        $campaign = factory(Campaign::class)->create([
            'week_id' => $week->id
        ]);

        $provinceID = $campaign->continent->provinces()->inRandomOrder()->first()->id;

        $quest = factory(Quest::class)->create([
            'province_id' => $provinceID
        ]);

        $diffProvinceID = Province::query()->where('id', '!=', $provinceID)->inRandomOrder()->first()->id;

        $campaign->squad->province_id = $diffProvinceID;
        $campaign->squad->save();

        try {
            $campaign->addQuest($quest);
        } catch (InvalidProvinceException $exception) {

            $this->assertEquals(0, $campaign->fresh()->quests->count());
            return;
        }

        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function adding_a_quest_that_it_has_already_added_will_throw_an_exception()
    {

        /** @var Week $week */
        $week = factory(Week::class)->create();
        Week::setTestCurrent($week);
        Carbon::setTestNow($week->everything_locks_at->copy()->subDays(1));


        /** @var Campaign $campaign */
        $campaign = factory(Campaign::class)->create([
            'week_id' => $week->id
        ]);

        $provinceID = $campaign->continent->provinces()->inRandomOrder()->first()->id;

        $quest = factory(Quest::class)->create([
            'province_id' => $provinceID
        ]);

        $campaign->quests()->attach($quest->id);

        $campaign->squad->province_id = $provinceID;
        $campaign->squad->save();

        try {
            $campaign->addQuest($quest);
        } catch (QuestExistsException $exception) {

            $this->assertEquals(1, $campaign->fresh()->quests->count());
            return;
        }

        $this->fail("Exception not thrown");
    }
}

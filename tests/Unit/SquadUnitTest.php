<?php

namespace Tests\Unit;

use App\Campaign;
use App\Exceptions\CampaignExistsException;
use App\Exceptions\NotBorderedByException;
use App\Exceptions\WeekLockedException;
use App\Province;
use App\Squad;
use App\Stash;
use App\StoreHouse;
use App\Weeks\Week;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SquadUnitTest extends TestCase
{

    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_can_retrieve_a_local_store_house()
    {
        /** @var Squad $squad */
        $squad = factory(Squad::class)->create();

        /** @var StoreHouse $storeHouse */
        $storeHouse = factory(StoreHouse::class)->create([
            'squad_id' => $squad->id,
            'province_id' => $squad->province_id
        ]);

        /** @var StoreHouse $storeHouseFound */
        $storeHouseFound = $squad->getLocalStoreHouse();

        $this->assertEquals($storeHouse->id, $storeHouseFound->id, "The store house was found");
    }

    /**
     * @test
     */
    public function it_will_not_retrieve_a_local_store_house_if_not_at_the_same_province()
    {
        /** @var Squad $squad */
        $squad = factory(Squad::class)->create();

        /** @var StoreHouse $storeHouse */
        $storeHouse = factory(StoreHouse::class)->create([
            'squad_id' => $squad->id,
            'province_id' => $squad->province_id
        ]);

        $province = Province::where('id', '!=', $squad->province_id)->inRandomOrder()->first();

        $squad->province_id = $province->id;
        $squad->save();

        $this->assertEquals($storeHouse->id, $squad->storeHouses()->first()->id, "Squad still owns the store house");

        $storeHouseFound = $squad->getLocalStoreHouse();

        $this->assertEquals(null, $storeHouseFound, "The store house was not found");
    }

    /**
     * @test
     */
    public function it_will_create_or_retrieve_a_local_stash()
    {
        /** @var Squad $squad */
        $squad = factory(Squad::class)->create();

        $stash = $squad->getLocalStash();

        $this->assertInstanceOf(Stash::class, $stash, "Stash exists");
    }

    /**
     * @test
     */
    public function it_will_not_create_another_stash_at_the_same_location()
    {
        /** @var Squad $squad */
        $squad = factory(Squad::class)->create();

        $stash = $squad->getLocalStash();

        $stashTwo = $squad->getLocalStash();

        $this->assertEquals($stash->id, $stashTwo->id, "Stashes are the same");
    }

    /**
     * @test
     */
    public function it_will_create_a_new_stash_if_no_current_one_at_current_province()
    {
        /** @var Squad $squad */
        $squad = factory(Squad::class)->create();

        $firstStash = $squad->getLocalStash();

        $this->assertEquals(1, $squad->stashes()->count(), "Squad has a stash");

        $newProvince = Province::where('id', '!=', $squad->province_id)->inRandomOrder()->first();

        $squad->province_id = $newProvince->id;
        $squad->save();

        $secondStash = $squad->getLocalStash();

        $this->assertEquals(2, $squad->stashes()->count(), "Squad now has 2 stashes");
        $this->assertNotEquals($firstStash->id, $secondStash, "Stashes are different");

        $squad->province_id = $firstStash->province_id;
        $squad->save();

        $sameAsFirstStash = $squad->getLocalStash();

        $this->assertEquals(2, $squad->stashes()->count(), "Squad still has 2 stashes");
        $this->assertEquals($firstStash->id, $sameAsFirstStash->id, "Didn't create a new stash");
    }

    /**
     * @test
     */
    public function border_traveling_to_a_province_that_is_not_its_current_provinces_border_will_throw_an_exception()
    {
        /** @var Squad $squad */
        $squad = factory(Squad::class)->create();
        $originalProvince = $squad->province;
        $borderIDs = $originalProvince->borders()->pluck('id')->toArray();
        /** @var Province $invalidProvince */
        $invalidProvince = Province::query()->whereNotIn('id', $borderIDs)->inRandomOrder()->first();

        try {
            $squad->borderTravel($invalidProvince);

        } catch (NotBorderedByException $exception) {

            $this->assertEquals($originalProvince, $squad->province);
            return;
        }

        $this->fail("Exception was not thrown");
    }

    /**
     * @test
     */
    public function creating_a_campaign_when_one_exists_for_the_week_will_throw_an_exception()
    {
        /** @var Week $week */
        $week = factory(Week::class)->create();
        Week::setTestCurrent($week);
        Carbon::setTestNow($week->everything_locks_at->copy()->subDays(1));

        /** @var Campaign $campaign */
        $campaign = factory(Campaign::class)->create([
            'week_id' => $week->id
        ]);

        $squad = $campaign->squad;

        try {

            $squad->createCampaign();

        } catch (CampaignExistsException $exception) {

            $this->assertEquals(1, $squad->campaigns()->count());
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function creating_a_campaign_when_the_week_is_locked_will_throw_an_exception()
    {
        /** @var Week $week */
        $week = factory(Week::class)->create();
        Week::setTestCurrent($week);
        Carbon::setTestNow($week->everything_locks_at->copy()->addMinutes(15));

        /** @var Squad $squad */
        $squad = factory(Squad::class)->create();

        try {

            $squad->createCampaign();

        } catch (WeekLockedException $exception) {

            $this->assertEquals(0, $squad->campaigns()->count());
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function adding_a_hero_without_a_hero_post_will_throw_an_exception()
    {

    }

    /**
     * @test
     */
    public function adding_a_hero_without_a_matching_hero_post_will_throw_an_exception()
    {

    }
}

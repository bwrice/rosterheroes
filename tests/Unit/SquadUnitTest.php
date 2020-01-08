<?php

namespace Tests\Unit;

use App\Domain\Models\Campaign;
use App\Exceptions\CampaignExistsException;
use App\Exceptions\NotBorderedByException;
use App\Exceptions\WeekLockedException;
use App\Domain\Models\Province;
use App\Domain\Models\Squad;
use App\Domain\Models\Stash;
use App\Domain\Models\Residence;
use App\Domain\Models\Week;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
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

        /** @var \App\Domain\Models\Residence $storeHouse */
        $storeHouse = factory(Residence::class)->create([
            'squad_id' => $squad->id,
            'province_id' => $squad->province_id
        ]);

        /** @var Residence $storeHouseFound */
        $storeHouseFound = $squad->getLocalResidence();

        $this->assertEquals($storeHouse->id, $storeHouseFound->id, "The store house was found");
    }

    /**
     * @test
     */
    public function it_will_not_retrieve_a_local_store_house_if_not_at_the_same_province()
    {
        /** @var \App\Domain\Models\Squad $squad */
        $squad = factory(Squad::class)->create();

        /** @var Residence $storeHouse */
        $storeHouse = factory(Residence::class)->create([
            'squad_id' => $squad->id,
            'province_id' => $squad->province_id
        ]);

        $province = Province::where('id', '!=', $squad->province_id)->inRandomOrder()->first();

        $squad->province_id = $province->id;
        $squad->save();

        $this->assertEquals($storeHouse->id, $squad->residences()->first()->id, "Squad still owns the store house");

        $storeHouseFound = $squad->getLocalResidence();

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
        /** @var \App\Domain\Models\Province $invalidProvince */
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
    public function it_is_not_combat_ready_if_there_are_no_heroes()
    {
        /** @var Squad $squad */
        $squad = factory(Squad::class)->create();
        $this->assertEquals(0, $squad->heroes->count());
        $this->assertFalse($squad->combatReady());
    }
}

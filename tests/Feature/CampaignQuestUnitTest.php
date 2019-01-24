<?php

namespace Tests\Feature;

use App\Campaign;
use App\Continent;
use App\Squad;
use App\Weeks\Week;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CampaignQuestUnitTest extends TestCase
{
    /**
     * @test
     */
    public function a_squad_will_create_a_new_campaign_for_a_continent_if_one_doesnt_exist()
    {
        /** @var Week $week */
        $week = factory(Week::class)->create();
        Week::setTestCurrent($week);

        /** @var Squad $squad */
        $squad = factory(Squad::class)->create();
        /** @var Continent $continent */
        $continent = Continent::query()->inRandomOrder()->first();
        $campaign = $squad->getContinentsCampaign($continent);

        $this->assertEquals($squad->id, $campaign->squad_id);
        $this->assertEquals($continent->id, $campaign->continent_id);
        $this->assertEquals($week->id, $campaign->week_id);
    }

    /**
     * @test
     */
    public function a_squad_will_return_an_existing_campaign_for_a_continent()
    {
        /** @var Week $week */
        $week = factory(Week::class)->create();
        Week::setTestCurrent($week);

        /** @var Squad $squad */
        $squad = factory(Squad::class)->create();
        /** @var Continent $continent */
        $continent = Continent::query()->inRandomOrder()->first();
        /** @var Campaign $campaign */
        $campaign = factory(Campaign::class)->create([
            'squad_id' => $squad->id,
            'week_id' => $week->id,
            'continent_id' => $continent->id
        ]);

        $retrievedCampaign = $squad->getContinentsCampaign($continent);
        $this->assertEquals($campaign->id, $retrievedCampaign->id);
    }

    /**
     * @test
     */
    public function an_exception_will_throw_if_a_campaign_exists_for_another_continent()
    {
        /** @var Week $week */
        $week = factory(Week::class)->create();
        Week::setTestCurrent($week);

        /** @var Squad $squad */
        $squad = factory(Squad::class)->create();
        /** @var Continent $continent */
        $continent = Continent::query()->inRandomOrder()->first();
        /** @var Campaign $campaign */
        $campaign = factory(Campaign::class)->create([
            'squad_id' => $squad->id,
            'week_id' => $week->id,
            'continent_id' => $continent->id
        ]);

        $diffContinent = Continent::where('id', '!=', $continent->id)->inRandomOrder()->first();

        try {
            $campaignRetrieved = $squad->getContinentsCampaign($diffContinent);
        } catch ( \Exception $exception ) {
            $this->assertEquals(1, $squad->campaigns()->count());
            return;
        }

        $this->fail("Exceptino not thrown");
    }

}

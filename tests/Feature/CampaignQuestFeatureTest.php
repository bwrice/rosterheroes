<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CampaignQuestFeatureTest extends TestCase
{
    /**
     * @test
     */
    public function a_campaign_can_join_a_quest()
    {
        $this->json('POST');
    }
}

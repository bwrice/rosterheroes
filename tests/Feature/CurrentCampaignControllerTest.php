<?php

namespace Tests\Feature;

use App\Domain\Models\Squad;
use App\Domain\Models\Week;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CurrentCampaignControllerTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Squad */
    protected $squad;

    /** @var Week */
    protected $week;

    public function setUp(): void
    {
        parent::setUp();
        $this->squad = factory(Squad::class)->create();
        $this->week = factory(Week::class)->create();
        Week::setTestCurrent($this->week);
    }

    /**
     * @test
     */
    public function it_will_return_null_if_there_is_no_current_campaign()
    {
        $this->withoutExceptionHandling();

        Passport::actingAs($this->squad->user);

        $response = $this->get('/api/v1/squads/' . $this->squad->slug . '/current-campaign');
        $response->assertStatus(200)
            ->assertJson([
                'data' => null
            ]);
    }
}

<?php

namespace Tests\Feature;

use App\Domain\Models\League;
use App\External\Stats\MySportsFeed\MSFClient;
use App\External\Stats\MySportsFeed\MySportsFeed;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MySportsFeedTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_will_return_team_dtos()
    {
        $clientMock = \Mockery::mock(MSFClient::class);
        $clientMock->shouldReceive('getData')->andReturn([
            'teamStatsTotal' => [
                [
                    'name' => 'Some Team',
                    'city' => 'Some City',
                    'abbreviation' => 'SCT',
                    'id' => '123abc'
                ],
                [
                    'name' => 'Another Team',
                    'city' => 'Another City',
                    'abbreviation' => 'ANT',
                    'id' => '987zyx'
                ],
                [
                    'name' => 'Last Team',
                    'city' => 'Last City',
                    'abbreviation' => 'LCC',
                    'id' => 'def456'
                ]
            ]
        ]);

        // put the mock into the container
        app()->instance(MSFClient::class, $clientMock);

        $league = League::first();

        /** @var MySportsFeed $msfIntegration */
        $msfIntegration = app(MySportsFeed::class);
        $teamDTOs = $msfIntegration->getTeamDTOs($league);

        $this->assertEquals(3, $teamDTOs->count());
    }
}

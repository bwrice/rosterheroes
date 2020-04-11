<?php

namespace Tests\Feature;

use App\Domain\Models\Week;
use App\Facades\CurrentWeek;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class WeekControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_will_return_the_current_week()
    {
        $this->withExceptionHandling();

        /** @var Week $week */
        $week = factory(Week::class)->create();
        $week->wasRecentlyCreated = false; // set to false otherwise response returns 201
        CurrentWeek::setTestCurrent($week);

        $response = $this->get('api/v1/weeks/current');

        $response
            ->assertStatus(200)
            ->assertJson([
            'data' => [
                'uuid' => $week->uuid
            ]
        ]);
    }
}
